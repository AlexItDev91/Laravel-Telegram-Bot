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

    public function test_qodana_github_workflow_is_configured_safely(): void
    {
        $workflow = Yaml::parseFile(__DIR__.'/../../.github/workflows/qodana.yml');

        $this->assertIsArray($workflow);
        $this->assertSame('Qodana', $workflow['name']);
        $this->assertSame(['main'], $workflow['on']['push']['branches']);
        $this->assertContains('qodana.yaml', $workflow['on']['push']['paths']);
        $this->assertContains('src/**', $workflow['on']['pull_request']['paths']);
        $this->assertArrayHasKey('workflow_dispatch', $workflow['on']);
        $this->assertSame('read', $workflow['permissions']['contents']);
        $this->assertSame('write', $workflow['permissions']['checks']);
        $this->assertSame('write', $workflow['permissions']['pull-requests']);
        $this->assertSame('true', $workflow['env']['FORCE_JAVASCRIPT_ACTIONS_TO_NODE24']);
        $this->assertSame('${{ secrets.QODANA_TOKEN }}', $workflow['env']['QODANA_TOKEN']);

        $steps = $workflow['jobs']['qodana']['steps'];

        $this->assertSame('Skip Qodana when token is not configured', $steps[0]['name']);
        $this->assertSame('${{ env.QODANA_TOKEN == \'\' }}', $steps[0]['if']);
        $this->assertSame('actions/checkout@v6', $steps[1]['uses']);
        $this->assertSame(0, $steps[1]['with']['fetch-depth']);
        $this->assertSame('${{ github.event_name == \'pull_request\' && github.event.pull_request.head.sha || github.sha }}', $steps[1]['with']['ref']);
        $this->assertSame('JetBrains/qodana-action@v2026.1', $steps[2]['uses']);
        $this->assertSame('--within-docker false', $steps[2]['with']['args']);
    }
}
