# Notifications

The package includes a Laravel notification channel for sending Telegram messages from regular Laravel notifications.
Use it when the destination belongs to a notifiable model or when on-demand notifications fit the host application better than direct service calls.

## Notification Class

Return the channel class from `via()` and provide a `toTelegram()` method:

```php
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use Illuminate\Notifications\Notification;

class DeployFinished extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramNotificationMessage
    {
        return TelegramNotificationMessage::text('Deploy finished')
            ->parseMode('HTML')
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
- `channel`
- `chat_id`
- `message_thread_id`
- `direct_messages_topic_id`

When `channel` is present, the package sends through the configured Telegram channel and merges the channel defaults exactly like `TelegramBot::channel('alerts')`.
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
return TelegramNotificationMessage::text('Build failed')
    ->bot('support')
    ->to('123456789')
    ->thread('42')
    ->parseMode('HTML')
    ->protectContent();
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
    'bot' => 'support',
    'chat_id' => '123456789',
])->notify(new DeployFinished());

$fake->assertSentMessage(function (array $parameters, string $botName): bool {
    return $botName === 'support'
        && $parameters['chat_id'] === '123456789'
        && $parameters['text'] === 'Deploy finished';
});
```
