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
        $this->assertGreaterThan(200, count($index));
        $this->assertSame('Laravel Telegram Bot', $index[0]['title'] ?? null);
        $this->assertSame('overview.html', $index[0]['url'] ?? null);
        $this->assertStringContainsString('local-search.js', (string) $html);

        $getUpdates = $this->findSearchRecord($index, 'getUpdates');

        $this->assertSame('API Method Reference', $getUpdates['pageTitle'] ?? null);
        $this->assertSame('getUpdates', $getUpdates['title'] ?? null);
        $this->assertSame('method-reference.html#getupdates', $getUpdates['url'] ?? null);
        $this->assertStringContainsString('Raw call: call', $getUpdates['content'] ?? '');
        $this->assertStringContainsString('Linked from Method Index', $getUpdates['content'] ?? '');

        $consoleCommands = $this->findSearchRecord($index, 'Console commands', 'console-commands.html');

        $this->assertSame('console-commands.html', $consoleCommands['url'] ?? null);
        $this->assertStringContainsString('Linked from', $consoleCommands['content'] ?? '');

        unlink($site.'/overview.html');
        unlink($site.'/local-search-index.json');
        unlink($site.'/local-search.js');
        rmdir($site);
    }

    /**
     * @param  list<array<string, string>>  $index
     * @return array<string, string>
     */
    private function findSearchRecord(array $index, string $title, ?string $url = null): array
    {
        foreach ($index as $record) {
            if (($record['title'] ?? null) === $title && ($url === null || ($record['url'] ?? null) === $url)) {
                return $record;
            }
        }

        $this->fail('Search record ['.$title.']'.($url === null ? '' : ' at ['.$url.']').' was not found.');
    }
}
