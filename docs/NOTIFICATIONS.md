# Notifications

The package includes a Laravel notification channel for sending Telegram messages from regular Laravel notifications.
Use it when the destination belongs to a notifiable model or when on-demand notifications fit the host application better than direct service calls.

## Notification Class

Return the channel class from `via()` and provide a `toTelegram()` method:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class DeployFinished extends Notification
{
    private const string TEXT = 'Deploy finished';

    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text(self::TEXT)
            ->parseMode(TelegramParseMode::HTML)
            ->disableNotification();
    }
}
```

## Routing

For notifiable models, add `routeNotificationForTelegram()`:

```php
use Illuminate\Notifications\Notification;

public function routeNotificationForTelegram(Notification $notification): array|string|null
{
    return [
        'bot' => 'support',
        'chat_id' => (string) $this->telegram_chat_id,
    ];
}
```

The route may be a plain `chat_id` string or an array containing:

- `bot`
- `token` or `bot_token`
- `channel`
- `chat_id`
- `message_thread_id`
- `direct_messages_topic_id`
- `api_url`
- `timeout`

When `channel` is present, the package sends through the configured Telegram channel and merges the channel defaults exactly like `TelegramBot::channel('alerts')`.
When `token` is present, the package creates a short-lived client for that bot token without changing global configuration. Do not return both `bot` and `token` for the same notification route.
Route fields are only used when the notification payload does not already define the same field.

For on-demand notifications, use Laravel's anonymous routes:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use Illuminate\Support\Facades\Notification;

Notification::route('telegram', [
    'bot' => 'support',
    'chat_id' => '123456789',
])->notify(new DeployFinished());

Notification::route(TelegramBotNotificationChannel::class, [
    'channel' => 'alerts',
])->notify(new DeployFinished());

Notification::route('telegram', [
    'token' => $tenant->telegram_bot_token,
    'chat_id' => (string) $tenant->telegram_chat_id,
])->notify(new DeployFinished());
```

## Payloads

`toTelegram()` may return:

- `TelegramNotificationMessage`
- `string`
- `array`
- a typed `TelegramBotRequestData` DTO
- `null` to skip delivery

A string is sent as `sendMessage` text:

```php
public function toTelegram(object $notifiable): string
{
    return 'Plain notification text';
}
```

Use `TelegramNotificationMessage` for common message options:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class BuildFailed extends Notification
{
    private const string BOT = 'support';

    private const string CHAT_ID = '123456789';

    private const string TEXT = 'Build failed';

    private const string THREAD_ID = '42';

    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text(self::TEXT)
            ->bot(self::BOT)
            ->to(self::CHAT_ID)
            ->thread(self::THREAD_ID)
            ->parseMode(TelegramParseMode::HTML)
            ->protectContent();
    }
}
```

For a dynamic bot token inside the notification payload, use `botToken()`:

```php
public function toTelegram(object $notifiable): TelegramNotificationMessage
{
    return TelegramNotificationMessage::text('Tenant alert')
        ->botToken($notifiable->telegram_bot_token)
        ->to((string) $notifiable->telegram_chat_id);
}
```

Use `forMethod()` for methods other than `sendMessage`:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;

return TelegramNotificationMessage::forMethod(TelegramBotApiMethod::sendPhoto, [
    'photo' => 'photo-file-id',
    'caption' => 'Daily report',
]);
```

Arrays can use the same explicit shape:

```php
return [
    'method' => TelegramBotApiMethod::sendDocument,
    'parameters' => [
        'document' => 'document-file-id',
        'caption' => 'Report',
    ],
];
```

When `parameters` is a typed request DTO and `method` is omitted, the channel infers the method from the DTO class name.

Typed outbound request DTOs infer their Telegram method from the DTO class name:

```php
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;

return new SendPhotoData(
    chatId: '123456789',
    photo: 'photo-file-id',
    caption: 'Daily report',
);
```

Generic `TelegramBotRequestData` instances do not carry a method name. Wrap them in `TelegramNotificationMessage::forMethod()` when using a generic DTO.

## Testing

Use the package fake in Laravel notification tests:

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use Illuminate\Support\Facades\Notification;

$fake = TelegramBot::fake();

Notification::route('telegram', [
    'token' => '222:fake-token',
    'chat_id' => '123456789',
])->notify(new DeployFinished());

$fake->assertSentMessageUsingToken('222:fake-token', function (array $parameters): bool {
    return $parameters['chat_id'] === '123456789'
        && $parameters['text'] === 'Deploy finished';
});
```
