<?php

namespace AlexItDev91\LaravelTelegramBot\Tests\Unit;

use PHPUnit\Framework\TestCase;

class WritersideStaticSearchTest extends TestCase
{
    public function test_static_search_builder_creates_index_and_injects_script(): void
    {
        $root = dirname(__DIR__, 2);
        $site = sys_get_temp_dir().'/writerside-static-search-'.bin2hex(random_bytes(6));

        mkdir($site, 0775, true);
        file_put_contents($site.'/overview.html', '<!doctype html><html><body><main>Docs</main></body></html>');

        $output = [];
        $exitCode = 0;

        exec('php '.escapeshellarg($root.'/scripts/build-writerside-static-search.php').' '.escapeshellarg($site).' 2>&1', $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));
        $this->assertFileExists($site.'/local-search-index.json');
        $this->assertFileExists($site.'/local-search.js');

        $index = json_decode((string) file_get_contents($site.'/local-search-index.json'), true, flags: JSON_THROW_ON_ERROR);
        $html = file_get_contents($site.'/overview.html');

        $this->assertIsArray($index);
        $this->assertNotEmpty($index);
        $this->assertSame('Laravel Telegram Bot', $index[0]['title'] ?? null);
        $this->assertSame('overview.html', $index[0]['url'] ?? null);
        $this->assertStringContainsString('local-search.js', (string) $html);

        unlink($site.'/overview.html');
        unlink($site.'/local-search-index.json');
        unlink($site.'/local-search.js');
        rmdir($site);
    }
}
