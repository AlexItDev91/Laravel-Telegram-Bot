<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands\Concerns;

use InvalidArgumentException;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

trait ResolvesTelegramBotConsoleInput
{
    private function botName(): string
    {
        $configured = $this->configuredBotNames();
        $option = $this->option('bot');

        if (is_string($option) && trim($option) !== '') {
            return trim($option);
        }

        if ($configured !== [] && $this->input->isInteractive()) {
            return (string) select(
                label: 'Which configured bot should be used?',
                options: $this->configuredBotOptions($configured),
                default: $this->defaultBotName(),
            );
        }

        if ($this->input->isInteractive()) {
            return text(
                label: 'Which configured bot should be used?',
                default: $this->defaultBotName(),
                required: true,
            );
        }

        return $this->defaultBotName();
    }

    /**
     * @param  list<string>  $bots
     * @return array<string, string>
     */
    private function configuredBotOptions(array $bots): array
    {
        $options = [];

        foreach ($bots as $bot) {
            $options[$bot] = $bot;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private function configuredChannelOptions(): array
    {
        $options = [];

        foreach ($this->configuredChannelNames() as $channel) {
            $options[$channel] = $channel;
        }

        return $options;
    }

    /**
     * @return list<string>
     */
    private function configuredBotNames(): array
    {
        $bots = config('telegram-bot.bots', []);

        if (! is_array($bots)) {
            return [];
        }

        return array_values(array_filter(
            array_map('strval', array_keys($bots)),
            static fn (string $bot): bool => trim($bot) !== '',
        ));
    }

    private function defaultBotName(): string
    {
        $default = config('telegram-bot.default', 'default');

        return is_string($default) && trim($default) !== ''
            ? trim($default)
            : 'default';
    }

    /**
     * @return list<string>
     */
    private function configuredChannelNames(): array
    {
        $channels = config('telegram-bot.channels', []);

        if (! is_array($channels)) {
            return [];
        }

        return array_values(array_filter(
            array_map('strval', array_keys($channels)),
            static fn (string $channel): bool => trim($channel) !== '',
        ));
    }

    /**
     * @return list<string>
     */
    private function stringListOption(string $name): array
    {
        $value = $this->option($name);

        if ($value === null || $value === false || $value === '') {
            return [];
        }

        $values = is_array($value) ? $value : [$value];

        return array_values(array_filter(
            array_map(static fn (mixed $item): string => trim((string) $item), $values),
            static fn (string $item): bool => $item !== '',
        ));
    }

    private function nullableStringOption(string $name): ?string
    {
        $value = $this->option($name);

        if ($value === null || $value === false) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function positiveIntOption(string $name): ?int
    {
        $value = $this->nullableStringOption($name);

        if ($value === null) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            throw new InvalidArgumentException("The [--{$name}] option must be a positive integer.");
        }

        return (int) $value;
    }

    private function nonNegativeIntOption(string $name): ?int
    {
        $value = $this->nullableStringOption($name);

        if ($value === null) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            throw new InvalidArgumentException("The [--{$name}] option must be a non-negative integer.");
        }

        return (int) $value;
    }
}
