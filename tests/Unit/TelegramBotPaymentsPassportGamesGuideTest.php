<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TelegramBotPaymentsPassportGamesGuideTest extends TestCase
{
    public function test_guide_documents_payments_passport_and_games_api(): void
    {
        $guide = file_get_contents(__DIR__.'/../../docs/PAYMENTS_PASSPORT_GAMES.md');
        $readme = file_get_contents(__DIR__.'/../../README.md');

        $this->assertIsString($guide);
        $this->assertIsString($readme);
        $this->assertStringContainsString('[Payments, Passport, And Games](https://alexitdev91.github.io/Laravel-Telegram-Bot/payments-passport-games.html)', $readme);
        $this->assertStringNotContainsString('[docs/PAYMENTS_PASSPORT_GAMES.md](docs/PAYMENTS_PASSPORT_GAMES.md)', $readme);

        foreach ([
            'SendInvoiceData',
            'CreateInvoiceLinkData',
            'AnswerShippingQueryData',
            'AnswerPreCheckoutQueryData',
            'GetStarTransactionsData',
            'RefundStarPaymentData',
            'EditUserStarSubscriptionData',
            'SendPaidMediaData',
            'InputPaidMediaLivePhoto',
            'SetPassportDataErrorsData',
            'PassportElementError',
            'PassportAuthorizationRequest',
            'TelegramPassportDecryptor',
            'SendGameData',
            'SetGameScoreData',
            'GetGameHighScoresData',
            'InlineQueryResultGame',
            'CallbackGame',
            'TelegramWebhookUpdate',
            'shippingQueryData()',
            'preCheckoutQueryData()',
            'successfulPaymentData()',
            'orderInfoData()',
            'https://core.telegram.org/bots/api',
            'https://core.telegram.org/bots/api-changelog',
            'https://core.telegram.org/passport',
        ] as $requiredInstruction) {
            $this->assertStringContainsString($requiredInstruction, $guide);
        }
    }
}
