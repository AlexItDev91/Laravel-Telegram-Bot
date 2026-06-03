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

$result = $telegram->bot('support')->call(TelegramBotApiMethod::SendMessage, [
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
