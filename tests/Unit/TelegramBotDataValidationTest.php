<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Games\GetGameHighScoresData;
use AlexItDev91\LaravelTelegramBot\DTO\Games\SetGameScoreData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\AnswerCallbackQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\EditMessageTextData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportAuthorizationRequest;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportElementError;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScope;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\PassportScopeElement;
use AlexItDev91\LaravelTelegramBot\DTO\Passport\SetPassportDataErrorsData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerPreCheckoutQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerShippingQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaPhoto;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendInvoiceData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendPaidMediaData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotConfigData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChannelConfigData;
use AlexItDev91\LaravelTelegramBot\Exceptions\TelegramBotConfigurationException;
use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TelegramBotDataValidationTest extends TestCase
{
    #[DataProvider('invalidPayloadProvider')]
    public function test_typed_dtos_reject_invalid_payloads_before_the_http_request(Closure $factory, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $factory();
    }

    #[DataProvider('invalidConfigurationProvider')]
    public function test_laravel_configuration_dtos_reject_invalid_values(Closure $factory, string $message): void
    {
        $this->expectException(TelegramBotConfigurationException::class);
        $this->expectExceptionMessage($message);

        $factory();
    }

    /**
     * @return array<string, array{factory: Closure(): mixed, message: string}>
     */
    public static function invalidPayloadProvider(): array
    {
        return [
            'empty invoice chat id' => [
                'factory' => static fn (): mixed => new SendInvoiceData('', 'Title', 'Description', 'payload', 'XTR', [
                    new LabeledPrice('Plan', 100),
                ]),
                'message' => 'Telegram Bot payload field [chat_id] must not be empty.',
            ],
            'empty send message text' => [
                'factory' => static fn (): mixed => new SendMessageData('100', ''),
                'message' => 'Telegram Bot payload field [text] must not be empty.',
            ],
            'edit message text without message reference' => [
                'factory' => static fn (): mixed => new EditMessageTextData('Updated text'),
                'message' => 'Telegram Bot payload requires either [inline_message_id] or both [chat_id] and [message_id].',
            ],
            'empty callback query id' => [
                'factory' => static fn (): mixed => new AnswerCallbackQueryData(''),
                'message' => 'Telegram Bot payload field [callback_query_id] must not be empty.',
            ],
            'negative callback cache time' => [
                'factory' => static fn (): mixed => new AnswerCallbackQueryData('callback-id', cacheTime: -1),
                'message' => 'Telegram Bot payload field [cache_time] must not be negative.',
            ],
            'empty invoice prices' => [
                'factory' => static fn (): mixed => new SendInvoiceData('100', 'Title', 'Description', 'payload', 'XTR', []),
                'message' => 'Telegram Bot payload field [prices] must not be empty.',
            ],
            'rejected pre-checkout without error message' => [
                'factory' => static fn (): mixed => AnswerPreCheckoutQueryData::reject('pre-checkout-id', ''),
                'message' => 'Telegram Bot payload field [error_message] must not be empty.',
            ],
            'accepted shipping without options' => [
                'factory' => static fn (): mixed => AnswerShippingQueryData::accept('shipping-id', []),
                'message' => 'Telegram Bot payload field [shipping_options] must not be empty.',
            ],
            'paid media without media' => [
                'factory' => static fn (): mixed => new SendPaidMediaData('100', 1, []),
                'message' => 'Telegram Bot payload field [media] must not be empty.',
            ],
            'paid media with invalid star count' => [
                'factory' => static fn (): mixed => new SendPaidMediaData('100', 0, [
                    new InputPaidMediaPhoto('photo-file-id'),
                ]),
                'message' => 'Telegram Bot payload field [star_count] must be greater than zero.',
            ],
            'extra can not override typed payload fields' => [
                'factory' => static fn (): mixed => new SendPaidMediaData('100', 1, [
                    new InputPaidMediaPhoto('photo-file-id'),
                ], extra: ['star_count' => 0]),
                'message' => 'Telegram Bot payload extra fields must not duplicate typed fields: [star_count].',
            ],
            'empty input paid media reference' => [
                'factory' => static fn (): mixed => (new InputPaidMediaPhoto(''))->toArray(),
                'message' => 'Telegram Bot payload field [media] must not be empty.',
            ],
            'game score without message reference' => [
                'factory' => static fn (): mixed => new SetGameScoreData(userId: '9007199254740991', score: 1200),
                'message' => 'Telegram Bot payload requires either [inline_message_id] or both [chat_id] and [message_id].',
            ],
            'game high scores without message reference' => [
                'factory' => static fn (): mixed => new GetGameHighScoresData(userId: '9007199254740991'),
                'message' => 'Telegram Bot payload requires either [inline_message_id] or both [chat_id] and [message_id].',
            ],
            'empty passport scope choices' => [
                'factory' => static fn (): mixed => PassportScopeElement::oneOfSeveral([])->toArray(),
                'message' => 'Telegram Bot payload field [one_of] must not be empty.',
            ],
            'passport error without file hashes' => [
                'factory' => static fn (): mixed => PassportElementError::files('bank_statement', [], 'Upload all pages')->toArray(),
                'message' => 'Telegram Bot payload field [file_hashes] must not be empty.',
            ],
            'passport errors request without errors' => [
                'factory' => static fn (): mixed => new SetPassportDataErrorsData('9007199254740991', []),
                'message' => 'Telegram Bot payload field [errors] must not be empty.',
            ],
            'passport authorization without public key' => [
                'factory' => static fn (): mixed => (new PassportAuthorizationRequest(
                    botId: '9007199254740991',
                    scope: new PassportScope(['email']),
                    publicKey: '',
                    nonce: 'nonce-123',
                ))->toArray(),
                'message' => 'Telegram Bot payload field [public_key] must not be empty.',
            ],
        ];
    }

    /**
     * @return array<string, array{factory: Closure(): mixed, message: string}>
     */
    public static function invalidConfigurationProvider(): array
    {
        return [
            'empty api url' => [
                'factory' => static fn (): mixed => new TelegramBotConfigData('123456:test-token', '', 10.0),
                'message' => 'Telegram Bot API URL must not be empty.',
            ],
            'invalid api url' => [
                'factory' => static fn (): mixed => new TelegramBotConfigData('123456:test-token', 'not a url', 10.0),
                'message' => 'Telegram Bot API URL must be a valid URL.',
            ],
            'invalid timeout' => [
                'factory' => static fn (): mixed => new TelegramBotConfigData('123456:test-token', 'https://api.telegram.test', 0.0),
                'message' => 'Telegram Bot timeout must be greater than zero.',
            ],
            'channel without chat id' => [
                'factory' => static fn (): mixed => new TelegramChannelConfigData('default', ''),
                'message' => 'Telegram Bot channel chat_id must not be empty.',
            ],
        ];
    }
}
