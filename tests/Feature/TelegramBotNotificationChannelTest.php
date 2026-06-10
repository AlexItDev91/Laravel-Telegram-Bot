<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Feature;

use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\Messages\SendPhotoData;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Facades\TelegramBot;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramBotNotificationChannel;
use AlexItDev91\LaravelTelegramBot\Laravel\Notifications\TelegramNotificationMessage;
use AlexItDev91\LaravelTelegramBot\Tests\TestCase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class TelegramBotNotificationChannelTest extends TestCase
{
    public function test_notification_channel_sends_to_configured_telegram_channel(): void
    {
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'support',
            'chat_id' => '-1001234567890',
            'message_thread_id' => '42',
        ]);

        $fake = TelegramBot::fake();

        (new TelegramNotificationChannelNotifiable())->notify(new TelegramTextNotification());

        $fake->assertSentMessageToChannel('alerts', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['chat_id'] === '-1001234567890'
                && $parameters['message_thread_id'] === '42'
                && $parameters['text'] === 'Deploy finished'
                && $parameters['parse_mode'] === 'HTML';
        });
    }

    public function test_notification_channel_sends_to_anonymous_telegram_route(): void
    {
        $fake = TelegramBot::fake();
        $notifiable = new AnonymousNotifiable();
        $notifiable->route(TelegramBotNotificationChannel::class, [
            'bot' => 'support',
            'chat_id' => '123456789',
        ]);

        $notifiable->notify(new TelegramStringNotification());

        $fake->assertCalled('sendMessage', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['chat_id'] === '123456789'
                && $parameters['text'] === 'Plain notification text';
        });
    }

    public function test_notification_channel_sends_to_dynamic_bot_token_route(): void
    {
        $fake = TelegramBot::fake();
        $notifiable = new AnonymousNotifiable();
        $notifiable->route(TelegramBotNotificationChannel::class, [
            'token' => '222:secret-token',
            'chat_id' => '123456789',
        ]);

        $notifiable->notify(new TelegramStringNotification());

        $fake->assertSentMessageUsingToken('222:secret-token', function (array $parameters): bool {
            return $parameters['chat_id'] === '123456789'
                && $parameters['text'] === 'Plain notification text';
        });
        $fake->assertNoTokenLeakage('222:secret-token');
    }

    public function test_notification_message_can_use_dynamic_bot_token_with_configured_channel(): void
    {
        config()->set('telegram-bot.channels.alerts', [
            'bot' => 'support',
            'chat_id' => '-1001234567890',
        ]);

        $fake = TelegramBot::fake();

        (new TelegramNotificationMissingRouteNotifiable())->notify(new TelegramDynamicTokenChannelNotification());

        $fake->assertSentMessageUsingToken('222:secret-token', function (array $parameters): bool {
            return $parameters['chat_id'] === '-1001234567890'
                && $parameters['text'] === 'Dynamic channel notification';
        });
        $fake->assertNoTokenLeakage('222:secret-token');
    }

    public function test_notification_channel_accepts_array_method_payloads(): void
    {
        $fake = TelegramBot::fake();
        $notifiable = new AnonymousNotifiable();
        $notifiable->route(TelegramBotNotificationChannel::class, '123456789');

        $notifiable->notify(new TelegramArrayNotification());

        $fake->assertCalled(TelegramBotApiMethod::sendPhoto, function (array $parameters, string $botName): bool {
            return $botName === 'default'
                && $parameters['chat_id'] === '123456789'
                && $parameters['photo'] === 'photo-file-id'
                && $parameters['caption'] === 'Report';
        });
    }

    public function test_notification_channel_infers_method_from_array_parameters_request_data(): void
    {
        $fake = TelegramBot::fake();

        (new TelegramNotificationMissingRouteNotifiable())->notify(new TelegramArrayParametersDataNotification());

        $fake->assertCalled('sendPhoto', function (array $parameters, string $botName): bool {
            return $botName === 'default'
                && $parameters['chat_id'] === '123456789'
                && $parameters['photo'] === 'photo-file-id'
                && $parameters['caption'] === 'Array DTO payload';
        });
    }


    public function test_notification_channel_accepts_typed_request_data(): void
    {
        $fake = TelegramBot::fake();
        $notifiable = new AnonymousNotifiable();
        $notifiable->route(TelegramBotNotificationChannel::class, [
            'bot' => 'support',
            'chat_id' => '123456789',
        ]);

        $notifiable->notify(new TelegramTypedDataNotification());

        $fake->assertCalled('sendMessage', function (array $parameters, string $botName): bool {
            return $botName === 'support'
                && $parameters['chat_id'] === '123456789'
                && $parameters['text'] === 'Typed payload';
        });
    }

    public function test_notification_channel_infers_telegram_method_from_typed_request_data(): void
    {
        $fake = TelegramBot::fake();

        (new TelegramNotificationMissingRouteNotifiable())->notify(new TelegramTypedPhotoNotification());

        $fake->assertCalled('sendPhoto', function (array $parameters, string $botName): bool {
            return $botName === 'default'
                && $parameters['chat_id'] === '123456789'
                && $parameters['photo'] === 'photo-file-id'
                && $parameters['caption'] === 'Typed photo payload';
        });
    }

    public function test_notification_channel_requires_explicit_method_for_generic_request_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram notification request DTO method could not be inferred.');

        TelegramBot::fake();

        (new TelegramNotificationMissingRouteNotifiable())->notify(new TelegramGenericRequestDataNotification());
    }

    public function test_notification_channel_requires_route_or_channel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram notification requires a chat_id route or a configured Telegram channel.');

        TelegramBot::fake();

        (new TelegramNotificationMissingRouteNotifiable())->notify(new TelegramStringNotification());
    }
}

final class TelegramNotificationChannelNotifiable
{
    use Notifiable;

    /**
     * @return array<string, string>
     */
    public function routeNotificationForTelegram(Notification $notification): array
    {
        return ['channel' => 'alerts'];
    }
}

final class TelegramNotificationMissingRouteNotifiable
{
    use Notifiable;
}

final class TelegramTextNotification extends Notification
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
            ->parseMode(TelegramParseMode::HTML);
    }
}

final class TelegramStringNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): string
    {
        return 'Plain notification text';
    }
}

final class TelegramDynamicTokenChannelNotification extends Notification
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
        return TelegramNotificationMessage::text('Dynamic channel notification')
            ->channel('alerts')
            ->botToken('222:secret-token');
    }
}

final class TelegramArrayNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    /**
     * @return array{method: TelegramBotApiMethod, parameters: array<string, mixed>}
     */
    public function toTelegram(object $notifiable): array
    {
        return [
            'method' => TelegramBotApiMethod::sendPhoto,
            'parameters' => [
                'photo' => 'photo-file-id',
                'caption' => 'Report',
            ],
        ];
    }
}

final class TelegramArrayParametersDataNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    /**
     * @return array{parameters: SendPhotoData}
     */
    public function toTelegram(object $notifiable): array
    {
        return [
            'parameters' => new SendPhotoData('123456789', 'photo-file-id', caption: 'Array DTO payload'),
        ];
    }
}

final class TelegramTypedDataNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): SendMessageData
    {
        return new SendMessageData('123456789', 'Typed payload');
    }
}

final class TelegramTypedPhotoNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): SendPhotoData
    {
        return new SendPhotoData('123456789', 'photo-file-id', caption: 'Typed photo payload');
    }
}

final class TelegramGenericRequestDataNotification extends Notification
{
    /**
     * @return list<class-string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotNotificationChannel::class];
    }

    public function toTelegram(object $notifiable): \AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData
    {
        return \AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData::fromArray([
            'chat_id' => '123456789',
            'text' => 'Generic payload',
        ]);
    }
}
