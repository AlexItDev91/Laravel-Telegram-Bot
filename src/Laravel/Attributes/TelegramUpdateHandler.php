<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class TelegramUpdateHandler
{
    /**
     * @param  list<class-string|callable>  $middleware
     */
    public function __construct(
        public string $type,
        public array $middleware = [],
    ) {
        //
    }
}
