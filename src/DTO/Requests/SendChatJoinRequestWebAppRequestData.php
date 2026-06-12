<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `sendChatJoinRequestWebApp`.
 */
final readonly class SendChatJoinRequestWebAppRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'sendChatJoinRequestWebApp';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $chatJoinRequestQueryId,
        string $webAppUrl,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'chat_join_request_query_id' => $chatJoinRequestQueryId,
            'web_app_url' => $webAppUrl,
        ], $extra)));
    }
}
