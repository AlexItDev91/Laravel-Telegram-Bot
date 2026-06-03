<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

class Php84StrictnessTest extends TestCase
{
    public function test_source_class_constants_are_typed(): void
    {
        $untypedConstants = [];

        foreach ($this->sourceClasses() as $class) {
            $reflection = new ReflectionClass($class);

            foreach ($reflection->getReflectionConstants() as $constant) {
                if ($constant->getDeclaringClass()->getName() !== $reflection->getName()) {
                    continue;
                }

                if ($constant->isEnumCase()) {
                    continue;
                }

                if ($constant->getType() === null) {
                    $untypedConstants[] = $reflection->getName().'::'.$constant->getName();
                }
            }
        }

        sort($untypedConstants);

        $this->assertSame([], $untypedConstants, 'Expected every source class constant to declare a PHP type.');
    }

    /**
     * @return list<class-string>
     */
    private function sourceClasses(): array
    {
        $root = dirname(__DIR__, 2).'/src';
        $classes = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($root) + 1, -4);
            $class = 'AlexItDev91\\LaravelTelegramBot\\'.str_replace('/', '\\', $relativePath);

            if (class_exists($class) || interface_exists($class) || enum_exists($class)) {
                $classes[] = $class;
            }
        }

        sort($classes);

        return $classes;
    }
}
