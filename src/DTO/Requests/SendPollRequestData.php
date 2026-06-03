<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPollType;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;

/**
 * Generated typed request builder for Telegram Bot API method `sendPoll`.
 */
final readonly class SendPollRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendPoll';

    /**
     * @param  array<string|int, mixed>  $options
     * @param  array<string|int, mixed>|null  $questionEntities
     * @param  array<string|int, mixed>|null  $countryCodes
     * @param  array<string|int, mixed>|null  $correctOptionIds
     * @param  array<string|int, mixed>|null  $explanationEntities
     * @param  TelegramBotData|array<string|int, mixed>|null  $explanationMedia
     * @param  array<string|int, mixed>|null  $descriptionEntities
     * @param  TelegramBotData|array<string|int, mixed>|null  $media
     * @param  TelegramBotData|array<string|int, mixed>|null  $replyParameters
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        int|string $chatId,
        string $question,
        array $options,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        string|TelegramParseMode|null $questionParseMode = null,
        ?array $questionEntities = null,
        ?bool $isAnonymous = null,
        string|TelegramPollType|null $type = null,
        ?bool $allowsMultipleAnswers = null,
        ?bool $allowsRevoting = null,
        ?bool $shuffleOptions = null,
        ?bool $allowAddingOptions = null,
        ?bool $hideResultsUntilCloses = null,
        ?bool $membersOnly = null,
        ?array $countryCodes = null,
        ?array $correctOptionIds = null,
        ?string $explanation = null,
        string|TelegramParseMode|null $explanationParseMode = null,
        ?array $explanationEntities = null,
        TelegramBotData|array|null $explanationMedia = null,
        ?int $openPeriod = null,
        ?int $closeDate = null,
        ?bool $isClosed = null,
        ?string $description = null,
        string|TelegramParseMode|null $descriptionParseMode = null,
        ?array $descriptionEntities = null,
        TelegramBotData|array|null $media = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?bool $allowPaidBroadcast = null,
        ?string $messageEffectId = null,
        TelegramBotData|array|null $replyParameters = null,
        mixed $replyMarkup = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_id' => $chatId,
            'question' => $question,
            'options' => $options,
            'business_connection_id' => $businessConnectionId,
            'message_thread_id' => $messageThreadId,
            'question_parse_mode' => $questionParseMode,
            'question_entities' => $questionEntities,
            'is_anonymous' => $isAnonymous,
            'type' => $type,
            'allows_multiple_answers' => $allowsMultipleAnswers,
            'allows_revoting' => $allowsRevoting,
            'shuffle_options' => $shuffleOptions,
            'allow_adding_options' => $allowAddingOptions,
            'hide_results_until_closes' => $hideResultsUntilCloses,
            'members_only' => $membersOnly,
            'country_codes' => $countryCodes,
            'correct_option_ids' => $correctOptionIds,
            'explanation' => $explanation,
            'explanation_parse_mode' => $explanationParseMode,
            'explanation_entities' => $explanationEntities,
            'explanation_media' => $explanationMedia,
            'open_period' => $openPeriod,
            'close_date' => $closeDate,
            'is_closed' => $isClosed,
            'description' => $description,
            'description_parse_mode' => $descriptionParseMode,
            'description_entities' => $descriptionEntities,
            'media' => $media,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'message_effect_id' => $messageEffectId,
            'reply_parameters' => $replyParameters,
            'reply_markup' => $replyMarkup,
        ], $extra)));
    }
}
