# Deep Links

Telegram deep links pass compact startup parameters into bots and Mini Apps.

Official references:

- [Telegram Bot Features: Deep Linking](https://core.telegram.org/bots/features#deep-linking)
- [Telegram Mini Apps: Direct Links](https://core.telegram.org/bots/webapps#direct-link-mini-apps)
- [Telegram Mini Apps: Attachment Menu Links](https://core.telegram.org/bots/webapps#adding-bots-to-the-attachment-menu)

Telegram start parameters may contain only `A-Z`, `a-z`, `0-9`, `_`, and `-`, and Bot API deep-link payloads are limited to 64 characters. This package validates those limits before returning a URL.

## Link Helpers

```php
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramDeepLink;

$privateChat = TelegramDeepLink::start('CompanyBot', 'ref_42')->url();
$groupInstall = TelegramDeepLink::startGroup('CompanyBot', 'setup-42')->url();
$mainMiniApp = TelegramDeepLink::startApp('CompanyBot', 'cart_42', compact: true)->url();
$namedMiniApp = TelegramDeepLink::startApp('CompanyBot', 'cart_42', appName: 'shop')->url();
$attachmentMenu = TelegramDeepLink::startAttach('CompanyBot', 'upload_42', ['users', 'bots'])->url();
```

Generated examples:

```text
https://t.me/CompanyBot?start=ref_42
https://t.me/CompanyBot?startgroup=setup-42
https://t.me/CompanyBot?startapp=cart_42&mode=compact
https://t.me/CompanyBot/shop?startapp=cart_42
https://t.me/CompanyBot?startattach=upload_42&choose=users+bots
```

`TelegramDeepLink::bot('CompanyBot')` returns the plain bot URL.
The helper accepts bot usernames with or without the leading `@`.

## Signed Start Payloads

Use `TelegramStartPayloadSigner` when the payload should not be forgeable by users.
The signer creates compact payloads that still fit Telegram's 64-character start parameter limit for short values.

```php
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramDeepLink;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;

final readonly class ReferralLinkFactory
{
    public function __construct(
        private TelegramStartPayloadSigner $payloads,
    ) {
    }

    public function link(string $referralCode): string
    {
        $payload = $this->payloads->sign(
            payload: $referralCode,
            secret: (string) config('app.key'),
            ttlSeconds: 3600,
        );

        return TelegramDeepLink::start('CompanyBot', $payload)->url();
    }
}
```

Verify the payload inside a `/start` command handler:

```php
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramWebhookCommandHandler;
use AlexItDev91\LaravelTelegramBot\DeepLinks\TelegramStartPayloadSigner;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramDeepLinkException;
use AlexItDev91\LaravelTelegramBot\Laravel\TelegramWebhookCommand;

final readonly class StartCommand implements TelegramWebhookCommandHandler
{
    public function __construct(
        private TelegramStartPayloadSigner $payloads,
    ) {
    }

    public function handle(TelegramWebhookCommand $command, string $botName): mixed
    {
        try {
            $payload = $this->payloads->verify(
                signedPayload: $command->arguments(),
                secret: (string) config('app.key'),
            );
        } catch (TelegramDeepLinkException) {
            return ['ok' => false];
        }

        $referralCode = $payload->string();

        return ['ok' => true, 'referral' => $referralCode];
    }
}
```

The verified result exposes:

- `payload()`
- `string()`
- `array()`
- `expiresAt()`
- `expired($now)`
- `toArray()`

String payloads with TTLs leave the most room under Telegram's 64-character limit. Small array payloads are supported, but signed array payloads with expiry metadata may exceed the limit quickly; prefer compact IDs and load full context from your application database.

## Mini App Start Parameters

The same signed payload can be used with Mini App direct links:

```php
$payload = $payloads->sign('cart42', (string) config('app.key'), ttlSeconds: 600);

$url = TelegramDeepLink::startApp(
    botUsername: 'CompanyBot',
    payload: $payload,
    appName: 'shop',
)->url();
```

Validate `Telegram.WebApp.initData` with `TelegramMiniAppInitDataValidator`, then verify `startParam()` with `TelegramStartPayloadSigner` if the Mini App start parameter was signed.
