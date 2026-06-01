<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerPreCheckoutQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\AnswerShippingQueryData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\CreateInvoiceLinkData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\EditUserStarSubscriptionData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\GetStarTransactionsData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaLivePhoto;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaPhoto;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\InputPaidMediaVideo;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\LabeledPrice;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\RefundStarPaymentData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendInvoiceData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\SendPaidMediaData;
use AlexItDev91\LaravelTelegramBot\DTO\Payments\ShippingOption;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\TelegramBotClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class TelegramPaymentsApiTest extends TestCase
{
    public function test_send_invoice_data_calls_telegram_invoice_endpoint(): void
    {
        $history = [];
        $client = $this->client($history);

        $client->sendInvoice(new SendInvoiceData(
            chatId: '-1001234567890',
            title: 'Premium plan',
            description: 'Monthly subscription',
            payload: 'order-100',
            providerToken: '',
            currency: 'XTR',
            prices: [new LabeledPrice('Plan', 499)],
            needEmail: true,
            isFlexible: true,
            replyMarkup: [
                'inline_keyboard' => [
                    [['text' => 'Pay', 'pay' => true]],
                ],
            ],
        ));

        $payload = json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('/bot123456:test-token/sendInvoice', $history[0]['request']->getUri()->getPath());
        $this->assertSame([
            'chat_id' => '-1001234567890',
            'title' => 'Premium plan',
            'description' => 'Monthly subscription',
            'payload' => 'order-100',
            'provider_token' => '',
            'currency' => 'XTR',
            'prices' => [
                ['label' => 'Plan', 'amount' => 499],
            ],
            'need_email' => true,
            'is_flexible' => true,
            'reply_markup' => [
                'inline_keyboard' => [
                    [['text' => 'Pay', 'pay' => true]],
                ],
            ],
        ], $payload);
    }

    public function test_payment_query_and_star_request_data_serializes_official_parameters(): void
    {
        $shipping = AnswerShippingQueryData::accept('shipping-query', [
            new ShippingOption('fast', 'Fast delivery', [
                new LabeledPrice('Delivery', 150),
            ]),
        ]);
        $preCheckout = AnswerPreCheckoutQueryData::reject('pre-checkout', 'Out of stock');
        $subscription = new EditUserStarSubscriptionData('9007199254740991', 'charge-id', true);
        $refund = new RefundStarPaymentData('9007199254740991', 'charge-id');
        $transactions = new GetStarTransactionsData(offset: 10, limit: 25);
        $invoiceLink = new CreateInvoiceLinkData(
            title: 'Monthly plan',
            description: 'Subscription',
            payload: 'subscription-1',
            providerToken: '',
            currency: 'XTR',
            prices: [new LabeledPrice('Plan', 499)],
            subscriptionPeriod: 2592000,
        );

        $this->assertSame([
            'shipping_query_id' => 'shipping-query',
            'ok' => true,
            'shipping_options' => [
                [
                    'id' => 'fast',
                    'title' => 'Fast delivery',
                    'prices' => [
                        ['label' => 'Delivery', 'amount' => 150],
                    ],
                ],
            ],
        ], $shipping->json());

        $this->assertSame([
            'pre_checkout_query_id' => 'pre-checkout',
            'ok' => false,
            'error_message' => 'Out of stock',
        ], $preCheckout->json());

        $this->assertSame([
            'user_id' => '9007199254740991',
            'telegram_payment_charge_id' => 'charge-id',
            'is_canceled' => true,
        ], $subscription->json());

        $this->assertSame([
            'user_id' => '9007199254740991',
            'telegram_payment_charge_id' => 'charge-id',
        ], $refund->json());

        $this->assertSame([
            'offset' => 10,
            'limit' => 25,
        ], $transactions->json());

        $this->assertSame([
            'title' => 'Monthly plan',
            'description' => 'Subscription',
            'payload' => 'subscription-1',
            'provider_token' => '',
            'currency' => 'XTR',
            'prices' => [
                ['label' => 'Plan', 'amount' => 499],
            ],
            'subscription_period' => 2592000,
        ], $invoiceLink->json());
    }

    public function test_paid_media_data_supports_all_input_paid_media_variants_and_nested_uploads(): void
    {
        $history = [];
        $path = tempnam(sys_get_temp_dir(), 'telegram-paid-media-');
        file_put_contents($path, 'paid-media-photo');

        try {
            $client = $this->client($history);

            $client->sendPaidMedia(new SendPaidMediaData(
                chatId: '-1001234567890',
                starCount: 100,
                media: [
                    new InputPaidMediaPhoto(InputFile::fromPath($path, 'paid.jpg', 'image/jpeg')),
                    new InputPaidMediaVideo('video-file-id', thumbnail: 'thumb-file-id'),
                    new InputPaidMediaLivePhoto('live-video-file-id', 'live-photo-file-id'),
                ],
                payload: 'paid-media-order',
                caption: 'Paid bundle',
            ));

            $body = (string) $history[0]['request']->getBody();

            $this->assertSame('/bot123456:test-token/sendPaidMedia', $history[0]['request']->getUri()->getPath());
            $this->assertStringStartsWith('multipart/form-data;', $history[0]['request']->getHeaderLine('Content-Type'));
            $this->assertStringContainsString('name="media"', $body);
            $this->assertStringContainsString('"type":"photo","media":"attach:\/\/file_0"', $body);
            $this->assertStringContainsString('"type":"video","media":"video-file-id","thumbnail":"thumb-file-id"', $body);
            $this->assertStringContainsString('"type":"live_photo","media":"live-video-file-id","photo":"live-photo-file-id"', $body);
            $this->assertStringContainsString('name="file_0"', $body);
            $this->assertStringContainsString('filename="paid.jpg"', $body);
        } finally {
            unlink($path);
        }
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function client(array &$history): TelegramBotClient
    {
        return TelegramBotClient::make(
            token: '123456:test-token',
            apiUrl: 'https://api.telegram.test',
            httpClient: $this->fakeHttpClient($history),
        );
    }

    /**
     * @param  array<int, array{request: RequestInterface}>  $history
     */
    private function fakeHttpClient(array &$history): Client
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], json_encode(['ok' => true, 'result' => true], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));

        return new Client([
            'handler' => $handler,
            'http_errors' => false,
        ]);
    }
}
