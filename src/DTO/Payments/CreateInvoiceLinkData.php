<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Payments;

use AlexItDev91\LaravelTelegramBot\DTO\Concerns\BuildsTelegramBotPayload;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;

final readonly class CreateInvoiceLinkData extends TelegramBotRequestData
{
    use BuildsTelegramBotPayload;

    /**
     * @param  array<int, LabeledPrice|array<string, mixed>>  $prices
     * @param  array<int, int>|null  $suggestedTipAmounts
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        ?string $businessConnectionId = null,
        ?string $providerToken = null,
        ?int $subscriptionPeriod = null,
        ?int $maxTipAmount = null,
        ?array $suggestedTipAmounts = null,
        ?string $providerData = null,
        ?string $photoUrl = null,
        ?int $photoSize = null,
        ?int $photoWidth = null,
        ?int $photoHeight = null,
        ?bool $needName = null,
        ?bool $needPhoneNumber = null,
        ?bool $needEmail = null,
        ?bool $needShippingAddress = null,
        ?bool $sendPhoneNumberToProvider = null,
        ?bool $sendEmailToProvider = null,
        ?bool $isFlexible = null,
        array $extra = [],
    ) {
        parent::__construct(self::payload([
            'business_connection_id' => $businessConnectionId,
            'title' => $title,
            'description' => $description,
            'payload' => $payload,
            'provider_token' => $providerToken,
            'currency' => $currency,
            'prices' => $prices,
            'subscription_period' => $subscriptionPeriod,
            'max_tip_amount' => $maxTipAmount,
            'suggested_tip_amounts' => $suggestedTipAmounts,
            'provider_data' => $providerData,
            'photo_url' => $photoUrl,
            'photo_size' => $photoSize,
            'photo_width' => $photoWidth,
            'photo_height' => $photoHeight,
            'need_name' => $needName,
            'need_phone_number' => $needPhoneNumber,
            'need_email' => $needEmail,
            'need_shipping_address' => $needShippingAddress,
            'send_phone_number_to_provider' => $sendPhoneNumberToProvider,
            'send_email_to_provider' => $sendEmailToProvider,
            'is_flexible' => $isFlexible,
        ], $extra, ['title', 'description', 'payload', 'currency', 'prices']));
    }
}
