<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class TelegramCommand
{
    /**
     * @param  list<class-string|callable>  $middleware
     */
    public function __construct(
        public string $name,
        public array $middleware = [],
    ) {
        //
    }
}
