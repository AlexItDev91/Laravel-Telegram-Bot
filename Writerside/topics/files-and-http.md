# Files And HTTP

The client sends JSON for normal Telegram requests and multipart form data when any `InputFile` value is present.
This includes nested arrays such as `media` payloads for `sendMediaGroup`.

## Upload A Document

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->bot('support')->sendDocument([
    'chat_id' => '-1001234567890',
    'document' => InputFile::fromPath(storage_path('app/reports/daily.pdf')),
    'caption' => 'Daily report',
]);
```

## Upload Generated Contents Or Streams

Use `fromContents()` for generated reports or exports that already exist in memory:

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->bot('support')->sendDocument([
    'chat_id' => '-1001234567890',
    'document' => InputFile::fromContents('generated report', 'report.txt', 'text/plain'),
]);
```

Use `fromStream()` for PSR-7 streams and `fromResource()` for valid PHP stream resources:

```php
$resource = fopen(storage_path('app/reports/daily.pdf'), 'rb');

if ($resource === false) {
    throw new RuntimeException('Report file is not readable.');
}

$telegram->bot('support')->sendDocument([
    'chat_id' => '-1001234567890',
    'document' => InputFile::fromResource($resource, 'daily.pdf', 'application/pdf'),
]);
```

## Upload A Media Group

```php
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->bot('support')->sendMediaGroup([
    'chat_id' => '-1001234567890',
    'media' => [
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photos/first.jpg')),
            'caption' => 'First photo',
        ],
        [
            'type' => 'photo',
            'media' => InputFile::fromPath(storage_path('app/photos/second.jpg')),
        ],
    ],
]);
```

The package converts nested files to Telegram `attach://` references and adds the file streams to the multipart body.
Local path files are opened lazily by PSR-7 streams during the HTTP request. Provided streams and resources are reused as-is.

## Use Existing Telegram File IDs

If Telegram already has the file, pass the file ID string instead of `InputFile`:

```php
$telegram->bot('support')->sendPhoto([
    'chat_id' => '-1001234567890',
    'photo' => 'AgACAgQAAxkBAA...',
    'caption' => 'Existing Telegram file',
]);
```

## Download A File

Use `getFileData()` to resolve Telegram's relative `file_path`, then download the bytes through the same configured bot client:

```php
$file = $telegram->bot('support')->getFileData([
    'file_id' => 'telegram-file-id',
]);

if ($file->filePath() !== null) {
    $telegram->bot('support')->downloadFileTo(
        $file->filePath(),
        storage_path('app/telegram/report.pdf'),
    );
}
```

Use `downloadFile($filePath)` when the application needs the file contents as a string, or `fileUrl($filePath)` when it must hand Telegram's temporary URL to another internal service.
Temporary file URLs contain the bot token, so do not log or expose them.

## Custom HTTP Client In Laravel

Bind `GuzzleHttp\ClientInterface` before the Telegram client is resolved:

```php
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

$this->app->bind(ClientInterface::class, fn (): ClientInterface => new Client([
    'timeout' => 5,
    'http_errors' => false,
]));
```

The package always disables Guzzle HTTP exceptions per request so Telegram `ok: false` responses keep their API metadata.

## Direct Client Construction

```php
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;

$client = TelegramBotClient::make(
    token: getenv('TELEGRAM_BOT_TOKEN') ?: null,
    apiUrl: getenv('TELEGRAM_BOT_API_URL') ?: 'https://api.telegram.org',
    timeout: 10,
    httpClient: new Client(['http_errors' => false]),
);
```

## Local Bot API Server

Telegram supports running a local Bot API server for large uploads, local file paths, HTTP webhooks, local IP webhooks, and higher webhook connection limits.
When using it, set `TELEGRAM_BOT_API_URL` to the local server base URL and keep the same bot token configuration.

```text
TELEGRAM_BOT_API_URL=http://127.0.0.1:8081
```

Review Telegram's official Bot API documentation before switching to a local server, because file behavior and webhook behavior differ from the public `https://api.telegram.org` service.

## Response Contract

Telegram successful responses contain:

```json
{
  "ok": true,
  "result": {}
}
```

Failed responses contain:

```json
{
  "ok": false,
  "description": "Bad Request: chat not found",
  "error_code": 400,
  "parameters": {}
}
```

The package validates this shape.
Successful responses without `result`, failed responses without `description`, non-JSON responses, and transport failures raise `TelegramBotTransportException`.
Telegram `ok: false` responses raise `TelegramBotApiException`.
