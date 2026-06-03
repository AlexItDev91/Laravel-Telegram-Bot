<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotResultData;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiResultSchema;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use ReflectionMethod;

final class TelegramBotResultFactory
{
    public static function from(string|TelegramBotApiMethod $method, mixed $result): mixed
    {
        if ($result instanceof TelegramBotData) {
            return $result;
        }

        $methodName = $method instanceof TelegramBotApiMethod ? $method->value : $method;
        $schema = TelegramBotApiResultSchema::result($methodName);

        if ($schema['allows_bool'] && is_bool($result)) {
            return $result;
        }

        if ($schema['data_class'] !== null) {
            return $schema['list']
                ? self::mapListToDataClass($result, $schema['data_class'])
                : self::dataObject($result, $schema['data_class']);
        }

        return self::generic($result);
    }

    private static function generic(mixed $result): mixed
    {
        if (! is_array($result)) {
            return $result;
        }

        if (array_is_list($result)) {
            if (array_filter($result, static fn (mixed $item): bool => ! is_array($item)) !== []) {
                return $result;
            }

            return self::mapList($result, static fn (array $payload): TelegramBotResultData => TelegramBotResultData::fromPayload($payload));
        }

        return TelegramBotResultData::fromPayload($result);
    }

    /**
     * @template T of TelegramBotData
     *
     * @param  mixed  $result
     * @param  callable(array<string, mixed>): T  $mapper
     * @return list<T>|mixed
     */
    private static function mapList(mixed $result, callable $mapper): mixed
    {
        if (! is_array($result)) {
            return $result;
        }

        return array_map(
            static fn (array $payload): TelegramBotData => $mapper($payload),
            array_values(array_filter($result, static fn (mixed $item): bool => is_array($item))),
        );
    }

    /**
     * @param  class-string  $dataClass
     */
    private static function dataObject(mixed $result, string $dataClass): mixed
    {
        if (! is_array($result) || ! method_exists($dataClass, 'fromPayload')) {
            return $result;
        }

        $data = (new ReflectionMethod($dataClass, 'fromPayload'))->invoke(null, $result);

        return $data instanceof TelegramBotData ? $data : TelegramBotResultData::fromPayload($result);
    }

    /**
     * @param  class-string  $dataClass
     * @return list<TelegramBotData>|mixed
     */
    private static function mapListToDataClass(mixed $result, string $dataClass): mixed
    {
        return self::mapList(
            $result,
            static function (array $payload) use ($dataClass): TelegramBotData {
                $data = self::dataObject($payload, $dataClass);

                return $data instanceof TelegramBotData ? $data : TelegramBotResultData::fromPayload($payload);
            },
        );
    }
}
