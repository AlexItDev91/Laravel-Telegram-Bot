<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class QodanaConfigurationTest extends TestCase
{
    public function test_qodana_configuration_uses_php_linter_and_strict_quality_gate(): void
    {
        $config = Yaml::parseFile(__DIR__.'/../../qodana.yaml');

        $this->assertIsArray($config);
        $this->assertSame('1.0', $config['version']);
        $this->assertSame('qodana-php', $config['linter']);
        $this->assertSame('qodana.recommended', $config['profile']['name']);
        $this->assertSame('8.2', $config['php']['version']);
        $this->assertSame('composer install --no-interaction --prefer-dist --no-progress', $config['bootstrap']);
        $this->assertSame(0, $config['failureConditions']['severityThresholds']['any']);
    }
}
