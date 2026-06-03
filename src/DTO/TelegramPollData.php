<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

use Override;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPollType;

final readonly class TelegramPollData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    public function id(): ?string
    {
        return $this->stringAt('id');
    }

    public function question(): ?string
    {
        return $this->stringAt('question');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function questionEntitiesData(): array
    {
        return $this->messageEntitiesAt('question_entities');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function options(): array
    {
        return $this->listOfArraysAt('options');
    }

    public function totalVoterCount(): ?int
    {
        return $this->intAt('total_voter_count');
    }

    public function isClosed(): ?bool
    {
        return $this->boolAt('is_closed');
    }

    public function isAnonymous(): ?bool
    {
        return $this->boolAt('is_anonymous');
    }

    public function type(): ?string
    {
        return $this->stringAt('type');
    }

    public function typeEnum(): ?TelegramPollType
    {
        $type = $this->type();

        return $type !== null ? TelegramPollType::tryFrom($type) : null;
    }

    public function allowsMultipleAnswers(): ?bool
    {
        return $this->boolAt('allows_multiple_answers');
    }

    public function allowsRevoting(): ?bool
    {
        return $this->boolAt('allows_revoting');
    }

    public function membersOnly(): ?bool
    {
        return $this->boolAt('members_only');
    }

    /**
     * @return list<string>
     */
    public function countryCodes(): array
    {
        return $this->listOfStringsAt('country_codes');
    }

    /**
     * @return list<int>
     */
    public function correctOptionIds(): array
    {
        return $this->listOfIntegersAt('correct_option_ids');
    }

    public function explanation(): ?string
    {
        return $this->stringAt('explanation');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function explanationEntitiesData(): array
    {
        return $this->messageEntitiesAt('explanation_entities');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function explanationMedia(): ?array
    {
        return $this->arrayAt('explanation_media');
    }

    public function openPeriod(): ?int
    {
        return $this->intAt('open_period');
    }

    public function closeDate(): ?int
    {
        return $this->intAt('close_date');
    }

    public function description(): ?string
    {
        return $this->stringAt('description');
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    public function descriptionEntitiesData(): array
    {
        return $this->messageEntitiesAt('description_entities');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function media(): ?array
    {
        return $this->arrayAt('media');
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function arrayAt(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : null;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    /**
     * @return list<int>
     */
    private function listOfIntegersAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_int($item)));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listOfArraysAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_array($item)));
    }

    /**
     * @return list<string>
     */
    private function listOfStringsAt(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn (mixed $item): bool => is_string($item)));
    }

    /**
     * @return list<TelegramMessageEntityData>
     */
    private function messageEntitiesAt(string $key): array
    {
        return array_map(
            static fn (array $entity): TelegramMessageEntityData => TelegramMessageEntityData::fromPayload($entity),
            $this->listOfArraysAt($key),
        );
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
