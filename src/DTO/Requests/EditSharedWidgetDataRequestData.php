<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;



/**
 * Generated typed request builder for Telegram Bot API method `editSharedWidgetData`.
 */
final readonly class EditSharedWidgetDataRequestData extends TelegramBotApiRequestData
{
    public const string METHOD = 'editSharedWidgetData';

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function make(
        string $widgetId,
        string $publicData,
        ?int $cacheTime = null,
        array $extra = [],
    ): self {
        return new self(self::withoutNullValues(array_merge([
            'widget_id' => $widgetId,
            'public_data' => $publicData,
            'cache_time' => $cacheTime,
        ], $extra)));
    }
}
