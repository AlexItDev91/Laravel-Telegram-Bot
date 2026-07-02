<?php

namespace AlexItDev91\LaravelTelegramBot\Facades;

use Override;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotClient as TelegramBotClientContract;
use AlexItDev91\LaravelTelegramBot\Contracts\TelegramBotManager as TelegramBotManagerContract;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\InputFile;
use AlexItDev91\LaravelTelegramBot\Outbound\TelegramMessage;
use AlexItDev91\LaravelTelegramBot\TelegramBotChannel;
use AlexItDev91\LaravelTelegramBot\TelegramBotManager;
use AlexItDev91\LaravelTelegramBot\Testing\TelegramBotFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed call(string $method, array<string, mixed> $parameters = [])
 * @method static mixed callData(string $method, array<string, mixed> $parameters = [])
 * @method static TelegramBotClient bot(?string $name = null)
 * @method static TelegramBotClient botToken(string $token, ?string $apiUrl = null, ?float $timeout = null)
 * @method static TelegramBotChannel channel(string $name, ?string $bot = null, ?string $token = null, ?string $apiUrl = null, ?float $timeout = null)
 * @method static TelegramBotChannel to(string|int $chatId, ?string $bot = null, ?string $token = null, string|int|null $messageThreadId = null, string|int|null $directMessagesTopicId = null, ?string $apiUrl = null, ?float $timeout = null)
 * @method static mixed send(TelegramMessage $message)
 * @method static string fileUrl(string $filePath)
 * @method static string downloadFile(string $filePath)
 * @method static string downloadFileTo(string $filePath, string $destination)
 * @method static mixed text(string $text, string|int|null $to = null, string|int|null $messageThreadId = null, string|int|null $directMessagesTopicId = null)
 * @method static mixed photo(string|InputFile $photo, ?string $caption = null, string|int|null $to = null, string|int|null $messageThreadId = null, string|int|null $directMessagesTopicId = null)
 * @method static mixed document(string|InputFile $document, ?string $caption = null, string|int|null $to = null, string|int|null $messageThreadId = null, string|int|null $directMessagesTopicId = null)
 * @method static mixed getMe(array<string, mixed>|TelegramBotRequestData $parameters = [])
 * @method static TelegramUserData getMeData()
 * @method static mixed sendMessage(array<string, mixed>|TelegramBotRequestData $parameters = [])
 * @method static TelegramMessageData sendMessageData(array<string, mixed>|TelegramBotRequestData $parameters = [])
 * @method static mixed sendDocument(array<string, mixed>|TelegramBotRequestData $parameters = [])
 *
 * @see TelegramBotManager
 */
class TelegramBot extends Facade
{
    public static function fake(?TelegramBotFake $fake = null): TelegramBotFake
    {
        $fake ??= new TelegramBotFake();

        static::swap($fake);

        app()->instance('telegram-bot', $fake);
        app()->instance(TelegramBotManagerContract::class, $fake);
        app()->instance(TelegramBotClientContract::class, $fake);

        return $fake;
    }

    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return 'telegram-bot';
    }
}
