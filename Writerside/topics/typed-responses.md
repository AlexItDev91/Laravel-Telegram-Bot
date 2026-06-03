# Typed Responses

Raw Telegram Bot API methods still return the decoded `result` value exactly as Telegram sent it.
Use the typed response helpers when application code needs stable accessors instead of nested arrays.

## Raw And Typed Calls

```php
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;

$raw = TelegramBot::getMe();
$user = TelegramBot::getMeData();

$message = TelegramBot::channel('alerts')->sendMessageData([
    'text' => 'Deploy finished',
]);
```

`callData()` is the typed equivalent of `call()`:

```php
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;

$message = TelegramBot::callData(TelegramBotApiMethod::sendMessage, [
    'chat_id' => '123456789',
    'text' => 'Hello',
]);
```

When a method has no typed mapper yet, `callData()` returns the raw result.
The raw `call(method, parameters)` API remains available for newly released Telegram methods.

## Typed Helper Methods

| Helper | Returned DTO |
| --- | --- |
| `getMeData()` | `TelegramUserData` |
| `getChatData()` | `TelegramChatData` |
| `getChatMemberData()` | `TelegramChatMemberData` |
| `getChatAdministratorsData()` | `list<TelegramChatMemberData>` |
| `getFileData()` | `TelegramFileData` |
| `getWebhookInfoData()` | `TelegramWebhookInfoData` |
| `getUpdatesData()` | `list<TelegramWebhookUpdate>` |
| `sendMessageData()` | `TelegramMessageData` |
| `sendPhotoData()` | `TelegramMessageData` |
| `sendDocumentData()` | `TelegramMessageData` |
| `forwardMessageData()` | `TelegramMessageData` |
| `editMessageTextData()` | `TelegramMessageData|bool` |

The helpers are available on the concrete client, the root Laravel service, the facade, configured channels, and the test fake:

```php
$user = $telegram->bot('support')->getMeData();
$message = $telegram->channel('alerts')->sendMessageData(['text' => 'Deploy finished']);
$info = TelegramBot::bot('support')->getWebhookInfoData();
```

## Accessors

```php
$message = TelegramBot::channel('alerts')->sendMessageData([
    'text' => 'Deploy finished',
]);

$messageId = $message->messageId();
$chatId = $message->chat()?->id();
$text = $message->text();
$senderUsername = $message->from()?->username();
```

File and webhook status responses expose typed accessors as well:

```php
$file = TelegramBot::getFileData(['file_id' => 'file-id']);
$path = $file->filePath();

$webhook = TelegramBot::getWebhookInfoData();
$pending = $webhook->pendingUpdateCount();
$allowedUpdates = $webhook->allowedUpdates();
```

`getUpdatesData()` returns the same `TelegramWebhookUpdate` DTO used by the webhook receiver:

```php
$updates = TelegramBot::getUpdatesData(['limit' => 10]);

foreach ($updates as $update) {
    $chatId = $update->effectiveChat()?->id();
    $text = $update->effectiveMessage()?->text();
}
```

## Testing

The fake converts configured raw results through the same response mapper:

```php
$fake = TelegramBot::fake()
    ->result([
        'message_id' => 10,
        'date' => 1710000000,
        'chat' => ['id' => '123456789', 'type' => 'private'],
        'text' => 'Hello',
    ], 'sendMessage');

$message = TelegramBot::sendMessageData([
    'chat_id' => '123456789',
    'text' => 'Hello',
]);

$fake->assertSentMessage();
$message->messageId(); // 10
```
