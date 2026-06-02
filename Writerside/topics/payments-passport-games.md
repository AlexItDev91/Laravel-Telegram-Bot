# Payments, Passport, And Games

This guide documents the typed helper API for Telegram Bot API `10.0` Payments, Telegram Passport, paid media, and Games features.

Official sources:

- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Telegram Bot API changelog](https://core.telegram.org/bots/api-changelog)
- [Telegram Passport manual](https://core.telegram.org/passport)

The raw `call(method, parameters)` API and the generic native methods still remain available. These helpers only build documented Telegram payloads and keep responses as the raw Telegram `result` arrays or scalar values, matching the rest of the SDK.

## Shared Payload Objects

All typed request objects extend `TelegramBotRequestData`, so they can be passed directly to existing SDK methods:

```php
$telegram->bot('shop')->sendInvoice(new SendInvoiceData(
    chatId: '-1001234567890',
    title: 'Premium plan',
    description: 'Monthly subscription',
    payload: 'order-100',
    providerToken: '',
    currency: 'XTR',
    prices: [new LabeledPrice('Plan', 499)],
));
```

Nested DTOs are recursively serialized to arrays. Nested `InputFile` values still use the existing multipart `attach://` behavior.

Typed payload objects validate required fields, empty arrays, selected numeric constraints, and documented Telegram alternatives before the HTTP request is sent. For example, rejected shipping and pre-checkout answers require `error_message`, accepted shipping answers require `shipping_options`, and game score requests require either `inline_message_id` or both `chat_id` and `message_id`. Use `extra` only for additional Telegram fields; it may not duplicate constructor-backed typed fields.

## Payments

### Payment Methods And DTOs

| Telegram method | Typed request DTO |
| --- | --- |
| `sendInvoice` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\SendInvoiceData` |
| `createInvoiceLink` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\CreateInvoiceLinkData` |
| `answerShippingQuery` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerShippingQueryData` |
| `answerPreCheckoutQuery` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerPreCheckoutQueryData` |
| `getMyStarBalance` | no parameters |
| `getStarTransactions` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\GetStarTransactionsData` |
| `refundStarPayment` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\RefundStarPaymentData` |
| `editUserStarSubscription` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\EditUserStarSubscriptionData` |
| `sendPaidMedia` | `AlexItDev91\LaravelTelegramBot\DTO\Payments\SendPaidMediaData` |

### Payment Value Objects

- `LabeledPrice`
- `ShippingOption`
- `InputPaidMediaPhoto`
- `InputPaidMediaVideo`
- `InputPaidMediaLivePhoto`

### Invoice Example

```php
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendInvoiceData;

$telegram->bot('shop')->sendInvoice(new SendInvoiceData(
    chatId: '-1001234567890',
    title: 'Premium plan',
    description: 'Monthly subscription',
    payload: 'order-100',
    providerToken: '',
    currency: 'XTR',
    prices: [new LabeledPrice('Plan', 499)],
    needEmail: true,
    replyMarkup: [
        'inline_keyboard' => [
            [['text' => 'Pay', 'pay' => true]],
        ],
    ],
));
```

### Shipping And Pre-Checkout

```php
use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerPreCheckoutQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerShippingQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\ShippingOption;

$telegram->bot('shop')->answerShippingQuery(AnswerShippingQueryData::accept(
    shippingQueryId: (string) $update->shippingQueryData()?->id(),
    shippingOptions: [
        new ShippingOption('fast', 'Fast delivery', [
            new LabeledPrice('Delivery', 150),
        ]),
    ],
));

$telegram->bot('shop')->answerPreCheckoutQuery(
    AnswerPreCheckoutQueryData::accept((string) $update->preCheckoutQueryData()?->id()),
);
```

Use `AnswerShippingQueryData::reject($id, $message)` and `AnswerPreCheckoutQueryData::reject($id, $message)` for failures.

### Stars And Subscriptions

```php
use AlexItDev91\LaravelTelegramBot\DTO\Payments\CreateInvoiceLinkData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\EditUserStarSubscriptionData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\GetStarTransactionsData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\RefundStarPaymentData;

$link = $telegram->bot('shop')->createInvoiceLink(new CreateInvoiceLinkData(
    title: 'Monthly plan',
    description: 'Subscription',
    payload: 'subscription-1',
    providerToken: '',
    currency: 'XTR',
    prices: [new LabeledPrice('Plan', 499)],
    subscriptionPeriod: 2592000,
));

$balance = $telegram->bot('shop')->getMyStarBalance();
$transactions = $telegram->bot('shop')->getStarTransactions(new GetStarTransactionsData(limit: 25));

$telegram->bot('shop')->refundStarPayment(new RefundStarPaymentData(
    userId: '9007199254740991',
    telegramPaymentChargeId: 'charge-id',
));

$telegram->bot('shop')->editUserStarSubscription(new EditUserStarSubscriptionData(
    userId: '9007199254740991',
    telegramPaymentChargeId: 'charge-id',
    isCanceled: true,
));
```

### Paid Media

```php
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaPhoto;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaVideo;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendPaidMediaData;
use AlexItDev91\LaravelTelegramBot\InputFile;

$telegram->bot('shop')->sendPaidMedia(new SendPaidMediaData(
    chatId: '-1001234567890',
    starCount: 100,
    media: [
        new InputPaidMediaPhoto(InputFile::fromPath(storage_path('app/photo.jpg'))),
        new InputPaidMediaVideo('telegram-video-file-id'),
    ],
    payload: 'paid-media-order',
    caption: 'Paid bundle',
));
```

### Incoming Payment Updates

`TelegramWebhookUpdate` exposes convenience helpers for the payment-related update and message fields:

- `shippingQuery()`
- `preCheckoutQuery()`
- `shippingQueryData()`
- `preCheckoutQueryData()`
- `purchasedPaidMedia()`
- `purchasedPaidMediaData()`
- `invoice()`
- `successfulPayment()`
- `successfulPaymentData()`
- `refundedPayment()`

```php
$shippingQuery = $update->shippingQuery();
$typedShippingQuery = $update->shippingQueryData();
$typedPreCheckoutQuery = $update->preCheckoutQueryData();
$typedPaidMediaPurchase = $update->purchasedPaidMediaData();
$invoice = $update->invoice();
$payment = $update->successfulPayment();
$typedPayment = $update->successfulPaymentData();
$refund = $update->refundedPayment();

$typedShippingQuery?->invoicePayload();
$typedPreCheckoutQuery?->totalAmount();
$typedPreCheckoutQuery?->orderInfoData()?->email();
$typedPaidMediaPurchase?->paidMediaPayload();
$typedPayment?->telegramPaymentChargeId();
$typedPayment?->orderInfoData()?->name();
```

## Telegram Passport

### Passport Methods And DTOs

| Telegram method or flow | Typed helper |
| --- | --- |
| `setPassportDataErrors` | `AlexItDev91\LaravelTelegramBot\DTO\Passport\SetPassportDataErrorsData` |
| Passport element errors | `AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportElementError` |
| Passport scope payload | `AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScope` |
| Passport scope element | `AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScopeElement` |
| Authorization widget request payload | `AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportAuthorizationRequest` |
| Data and file decryption | `AlexItDev91\LaravelTelegramBot\Passport\TelegramPassportDecryptor` |

### Request Scope

```php
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportAuthorizationRequest;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScope;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScopeElement;

$request = new PassportAuthorizationRequest(
    botId: '123456789',
    scope: new PassportScope([
        PassportScopeElement::one('personal_details', nativeNames: true),
        PassportScopeElement::oneOfSeveral(['passport', 'identity_card'], selfie: true, translation: true),
        'email',
    ]),
    publicKey: file_get_contents(storage_path('app/telegram-passport-public.pem')),
    nonce: $nonce,
);

$payload = $request->toArray();
```

### Receiving And Decrypting Passport Data

```php
use AlexItDev91\LaravelTelegramBot\Passport\TelegramPassportDecryptor;

$passportData = $update->passportData();

$decrypted = (new TelegramPassportDecryptor())->decryptPassportData(
    passportData: $passportData,
    privateKey: file_get_contents(storage_path('app/telegram-passport-private.pem')),
    expectedNonce: $expectedNonce,
);

$firstElement = $decrypted['elements'][0] ?? null;
$personalDetails = $firstElement['decrypted_data'] ?? null;
```

`decryptPassportData()` verifies credentials hashes and the expected nonce. `decryptFileContents($encryptedContents, $fileCredentials)` decrypts file bytes after you download the encrypted file through `getFile`.

Never commit Passport private keys, user documents, decrypted personal data, or nonces.

### Passport Errors

```php
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportElementError;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\SetPassportDataErrorsData;

$telegram->bot('kyc')->setPassportDataErrors(new SetPassportDataErrorsData(
    userId: '9007199254740991',
    errors: [
        PassportElementError::dataField('personal_details', 'first_name', $dataHash, 'Fix first name'),
        PassportElementError::frontSide('passport', $fileHash, 'Upload a clearer front side'),
        PassportElementError::unspecified('address', $elementHash, 'Address can not be verified'),
    ],
));
```

`PassportElementError` covers all official error sources: `data`, `front_side`, `reverse_side`, `selfie`, `file`, `files`, `translation_file`, `translation_files`, and `unspecified`.

## Games

### Game Methods And DTOs

| Telegram method or object | Typed helper |
| --- | --- |
| `sendGame` | `AlexItDev91\LaravelTelegramBot\DTO\Games\SendGameData` |
| `setGameScore` | `AlexItDev91\LaravelTelegramBot\DTO\Games\SetGameScoreData` |
| `getGameHighScores` | `AlexItDev91\LaravelTelegramBot\DTO\Games\GetGameHighScoresData` |
| `CallbackGame` | `AlexItDev91\LaravelTelegramBot\DTO\Games\CallbackGame` |
| `InlineQueryResultGame` | `AlexItDev91\LaravelTelegramBot\DTO\Games\InlineQueryResultGame` |

### Sending Games

```php
use AlexItDev91\LaravelTelegramBot\DTO\Games\CallbackGame;
use AlexItDev91\LaravelTelegramBot\DTO\Games\SendGameData;

$telegram->bot('games')->sendGame(new SendGameData(
    chatId: '@target_bot',
    gameShortName: 'space_race',
    replyMarkup: [
        'inline_keyboard' => [
            [
                ['text' => 'Play', 'callback_game' => new CallbackGame()],
                ['text' => 'Scores', 'url' => 'https://example.com/scores'],
            ],
        ],
    ],
));
```

### Scores

```php
use AlexItDev91\LaravelTelegramBot\DTO\Games\GetGameHighScoresData;
use AlexItDev91\LaravelTelegramBot\DTO\Games\SetGameScoreData;

$telegram->bot('games')->setGameScore(new SetGameScoreData(
    userId: '9007199254740991',
    score: 1200,
    chatId: '-1001234567890',
    messageId: 55,
));

$scores = $telegram->bot('games')->getGameHighScores(new GetGameHighScoresData(
    userId: '9007199254740991',
    chatId: '-1001234567890',
    messageId: 55,
));
```

### Inline Games And Callbacks

```php
use AlexItDev91\LaravelTelegramBot\DTO\Games\InlineQueryResultGame;

$telegram->bot('games')->answerInlineQuery([
    'inline_query_id' => $inlineQueryId,
    'results' => [
        new InlineQueryResultGame('result-1', 'space_race'),
    ],
]);

$gameShortName = $update->gameShortName();
$game = $update->game();
```

`TelegramWebhookUpdate::game()` reads `message.game`; `gameShortName()` reads `callback_query.game_short_name`.
