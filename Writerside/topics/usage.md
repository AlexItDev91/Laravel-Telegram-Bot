# Usage

Use constructor injection for production Laravel code.
Use the Facade when it is more convenient in small or framework-style call sites.
Use a configured channel when the destination is reused.
Use raw `call(method, parameters)` for newly released Telegram methods.

## Constructor Injection

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final class SendTelegramAlert
{
    public function __construct(
        private TelegramBot $telegram,
    ) {
    }

    public function __invoke(): void
    {
        $this->telegram->channel('inbox')->sendMessage([
            'text' => 'New inbound email',
        ]);
    }
}
```

Type-hint `AlexItDev91\LaravelTelegramBot\TelegramBot` or `TelegramBotClient` when you want IDE autocomplete for every native Telegram method.
Type-hint `AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager` or `Contracts\TelegramBotClient` when you only need the stable core contract.

Use typed outbound DTOs for common message workflows when you want validation before the HTTP request:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardButton;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\InlineKeyboardMarkup;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\LinkPreviewOptions;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final readonly class DeployNotifier
{
    private const string BOT = 'support';

    private const string BUTTON_RETRY = 'Retry';

    private const string CALLBACK_RETRY = 'deploy:retry';

    private const string CHAT_ID = '-1001234567890';

    private const string TEXT = 'Deploy finished';

    public function __construct(private TelegramBot $telegram)
    {
    }

    public function send(): mixed
    {
        return $this->telegram->bot(self::BOT)->sendMessage(new SendMessageData(
            chatId: self::CHAT_ID,
            text: self::TEXT,
            linkPreviewOptions: LinkPreviewOptions::disabled(),
            replyMarkup: InlineKeyboardMarkup::singleButton(
                InlineKeyboardButton::callback(self::BUTTON_RETRY, self::CALLBACK_RETRY),
            ),
        ));
    }
}
```

Use the fluent `TelegramMessage` builder when the send flow is common and the destination is explicit:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;

final class TelegramFluentNotifier
{
    private const string TEXT_DEPLOY_FINISHED = 'Deploy finished';

    private const string PHOTO_FILE_ID = 'photo-file-id';

    private const string DOCUMENT_FILE_ID = 'document-file-id';

    public function send(string $tenantBotToken): void
    {
        TelegramBot::channel('alerts')->send(
            TelegramMessage::text(self::TEXT_DEPLOY_FINISHED),
        );

        TelegramBot::to('-1001234567890', token: $tenantBotToken)->send(
            TelegramMessage::photo(self::PHOTO_FILE_ID)->caption('Daily report'),
        );

        TelegramBot::botToken($tenantBotToken)->send(
            TelegramMessage::document(self::DOCUMENT_FILE_ID)
                ->to('-1001234567890')
                ->caption('Invoice'),
        );
    }
}
```

The builder supports `text()`, `photo()`, `document()`, `to()`, `caption()`, `parseMode()`, `replyMarkup()`, `silent()`, `protectContent()`, and `extra()`.
Use typed request DTOs or raw arrays when you need stricter method-specific validation or the full Telegram surface.

## Facade

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

TelegramBot::bot('support')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'New message',
]);

TelegramBot::channel('inbox')->sendMessage([
    'text' => 'New inbound email',
]);
```

## Dynamic Bots And Destinations

Use `to()` when the bot token or destination belongs to a tenant, customer, or runtime choice instead of package config:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

$token = $tenant->telegram_bot_token;
$chatId = (string) $tenant->telegram_channel_id;

TelegramBot::to($chatId, token: $token)->sendMessage([
    'text' => 'Tenant alert',
]);
```

The same flow works through constructor injection:

```php
use AlexItDev91\LaravelTelegramBot\TelegramBot;

final readonly class TenantAlert
{
    public function __construct(private TelegramBot $telegram)
    {
    }

    public function send(Tenant $tenant): mixed
    {
        return $this->telegram->to(
            chatId: (string) $tenant->telegram_channel_id,
            token: $tenant->telegram_bot_token,
        )->sendMessage([
            'text' => 'Tenant alert',
        ]);
    }
}
```

Use `botToken($token)` for full payload control, or override only the bot used by a configured channel:

```php
TelegramBot::botToken($token)->sendMessage([
    'chat_id' => $chatId,
    'text' => 'Direct dynamic payload',
]);

TelegramBot::channel('inbox', token: $token)->sendMessage([
    'text' => 'Configured destination, dynamic bot',
]);
```

Dynamic token calls create a short-lived client for the supplied token; they do not write to or mutate `config/telegram-bot.php`.

## Configured Channel

A configured channel injects the destination fields before calling Telegram:

```php
'channels' => [
    'inbox' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_INBOX_CHAT_ID'),
    ],
],
```

```php
$telegram->channel('inbox')->sendMessage([
    'text' => 'A new support ticket arrived.',
]);
```

For a forum topic:

```php
'channels' => [
    'deployments' => [
        'bot' => 'support',
        'chat_id' => env('TELEGRAM_DEPLOYMENTS_CHAT_ID'),
        'message_thread_id' => env('TELEGRAM_DEPLOYMENTS_MESSAGE_THREAD_ID'),
    ],
],
```

```php
$telegram->channel('deployments')->sendMessage([
    'text' => 'Deployment finished.',
]);
```

## Direct Bot Call

```php
$telegram->bot('support')->sendMessage([
    'chat_id' => '-1001234567890',
    'text' => 'Hello from Laravel.',
]);
```

All native Telegram methods accept:

```php
array|AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData $parameters = []
```

They return the decoded Telegram `result`.

## Raw API Escape Hatch

```php
$result = $telegram->bot('support')->call('newTelegramMethod', [
    'parameter' => 'value',
]);
```

The raw call path validates the method name, sends the parameters, and applies the same response parsing as native helpers.
It is intentionally retained so applications are not blocked when Telegram releases a method before this package adds a named helper.

## Enum Method Names

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

$result = $telegram->bot('support')->call(TelegramBotApiMethod::sendMessage, [
    'chat_id' => '-1001234567890',
    'text' => 'Enum-based call',
]);
```

## Files

Use `InputFile::fromPath` for local uploads:

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->channel('inbox')->sendDocument([
    'document' => InputFile::fromPath(storage_path('app/report.pdf')),
    'caption' => 'Daily report',
]);
```

Nested media arrays are converted to Telegram `attach://` multipart references automatically.
See [Files and HTTP](files-and-http.md).

## Error Handling

| Error source | Exception |
| --- | --- |
| Missing bot config or token | `TelegramBotConfigurationException` |
| Unknown configured bot | `TelegramBotNotConfiguredException` |
| Unknown configured channel | `TelegramBotChannelNotConfiguredException` |
| Telegram `ok: false` response | `TelegramBotApiException` |
| Transport failure or invalid API response shape | `TelegramBotTransportException` |

Example:

```php
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotApiException;

try {
    $telegram->channel('inbox')->sendMessage([
        'text' => 'Hello',
    ]);
} catch (TelegramBotApiException $exception) {
    $retryAfter = $exception->retryAfter();
    $migrateToChatId = $exception->migrateToChatId();

    report($exception);
}
```

`retryAfter()` reads Telegram `ResponseParameters.retry_after` for rate limits.
`migrateToChatId()` reads `ResponseParameters.migrate_to_chat_id` when a group is upgraded to a supergroup.

## Identifier Safety

Telegram user, chat, message, and topic identifiers can exceed 32-bit integer range.
Keep them as strings or 64-bit safe values in application code, config, databases, and logs.

## Testing With The Fake

In Laravel tests, replace the facade/manager with a recording fake:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

$fake = TelegramBot::fake()
    ->result(['message_id' => 10], 'sendMessage');

TelegramBot::sendMessage([
    'chat_id' => '123456789',
    'text' => 'Hello',
]);

$fake->assertSentMessage(function (array $parameters): bool {
    return $parameters['chat_id'] === '123456789'
        && $parameters['text'] === 'Hello';
});
```

Named bot calls are recorded with the bot name:

```php
TelegramBot::bot('support')->sendMessage([
    'chat_id' => '123456789',
    'text' => 'Hello support',
]);

$fake->assertCalled('sendMessage', function (array $parameters, string $botName): bool {
    return $botName === 'support';
});
```

Configured channel calls merge the channel `chat_id`, `message_thread_id`, and `direct_messages_topic_id` defaults before recording the API call:

```php
TelegramBot::channel('alerts')->sendMessage([
    'text' => 'Deploy finished',
]);

$fake->assertSentMessageToChannel('alerts', function (array $parameters, string $botName): bool {
    return $botName === 'support'
        && $parameters['chat_id'] === '-1001234567890'
        && $parameters['text'] === 'Deploy finished';
});
```

Use `assertNothingSent()` when a code path must not call Telegram.
