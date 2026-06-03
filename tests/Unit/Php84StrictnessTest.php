<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

class Php84StrictnessTest extends TestCase
{
    public function test_source_uses_php84_array_helpers_in_validation_paths(): void
    {
        $root = dirname(__DIR__, 2);

        foreach ([
            'src/DTO/TelegramBotRequestData.php' => 'array_any(',
            'src/DTO/TelegramWebhookUpdate.php' => 'array_find(',
            'src/Support/TelegramBotResultFactory.php' => 'array_all(',
        ] as $path => $helper) {
            $source = file_get_contents($root.'/'.$path);

            $this->assertIsString($source);
            $this->assertStringContainsString(
                $helper,
                $source,
                "Expected [$path] to keep using the PHP 8.4 [$helper] helper in its validation path.",
            );
        }
    }

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
