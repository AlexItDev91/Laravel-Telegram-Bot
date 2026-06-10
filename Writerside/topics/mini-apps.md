# Mini Apps

Use `TelegramMiniAppInitDataValidator` to validate `Telegram.WebApp.initData` on your server before trusting Mini App user, chat, or start-parameter values.

Official reference:

- [Validating data received via the Mini App](https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app)

The helper validates Telegram's bot-token HMAC flow:

- parse the raw query string from `Telegram.WebApp.initData`
- remove the `hash` field
- sort remaining fields alphabetically
- build the `key=value` data-check-string joined by line feeds
- derive the secret key with `HMAC_SHA256(bot_token, "WebAppData")`
- compare the calculated SHA-256 hex digest with Telegram's `hash` using a constant-time comparison
- optionally reject stale data with `auth_date` and `maxAgeSeconds`

Do not send `Telegram.WebApp.initDataUnsafe` to the backend. The frontend should send the raw `Telegram.WebApp.initData` string.

## Laravel Controller

The validator is registered in the Laravel container, so controllers, actions, services, and jobs can use constructor injection:

```php
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramMiniAppInitDataException;
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class TelegramMiniAppSessionController
{
    public function __construct(
        private TelegramMiniAppInitDataValidator $telegramMiniApps,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = $this->telegramMiniApps->validate(
                initData: (string) $request->string('initData'),
                botToken: (string) config('telegram-bot.token'),
                maxAgeSeconds: 300,
            );
        } catch (TelegramMiniAppInitDataException) {
            abort(401);
        }

        return response()->json([
            'telegram_user_id' => (string) $data->user()?->id(),
            'username' => $data->user()?->username(),
            'start_param' => $data->startParam(),
            'chat_type' => $data->chatType(),
        ]);
    }
}
```

Use the same helper with tenant-owned bots by passing the tenant token resolved by the host application:

```php
$data = $telegramMiniApps->validate(
    initData: (string) $request->string('initData'),
    botToken: $tenant->telegram_bot_token,
    maxAgeSeconds: 300,
);
```

## Framework-Agnostic Usage

The validator does not depend on Laravel:

```php
use AlexItDev91\LaravelTelegramBot\MiniApps\TelegramMiniAppInitDataValidator;

$validator = new TelegramMiniAppInitDataValidator();

$data = $validator->validate(
    initData: $initData,
    botToken: $botToken,
    maxAgeSeconds: 300,
);
```

Use `isValid()` when a boolean result fits better than exception handling:

```php
if (! $validator->isValid($initData, $botToken, maxAgeSeconds: 300)) {
    // Reject the request.
}
```

## Accessors

`validate()` returns `TelegramMiniAppInitData`.

Common accessors:

- `raw()`
- `fields()`
- `hash()`
- `signature()`
- `queryId()`
- `user()`
- `receiver()`
- `chat()`
- `chatType()`
- `chatInstance()`
- `startParam()`
- `canSendAfter()`
- `authDate()`
- `toArray()`

`user()` and `receiver()` return `TelegramMiniAppUserData` with accessors such as `id()`, `firstName()`, `lastName()`, `username()`, `languageCode()`, `isPremium()`, `addedToAttachmentMenu()`, `allowsWriteToPm()`, and `photoUrl()`.

`chat()` returns `TelegramMiniAppChatData` with `id()`, `type()`, `title()`, `username()`, and `photoUrl()`.

Keep raw access available with `fields()` and `toArray()` when Telegram adds Mini App fields before the SDK adds dedicated accessors.

## Third-Party Validation

Telegram also documents Ed25519-based third-party validation using the `signature` field and Telegram's public keys. This package validates Mini App init data with the bot token. Use the official third-party flow separately when you need a service without access to the bot token to verify the data.
