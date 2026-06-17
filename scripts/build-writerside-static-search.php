#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$site = $argv[1] ?? null;

if (! is_string($site) || trim($site) === '') {
    fwrite(STDERR, "Usage: php scripts/build-writerside-static-search.php <site-directory>\n");
    exit(1);
}

$site = rtrim($site, '/');

if (! is_dir($site)) {
    fwrite(STDERR, "Site directory [$site] does not exist.\n");
    exit(1);
}

$index = buildIndex($root);
$indexPath = $site.'/local-search-index.json';
$scriptPath = $site.'/local-search.js';
$scriptSource = $root.'/Writerside/cfg/static/local-search.js';

if (file_put_contents($indexPath, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)) === false) {
    fwrite(STDERR, "Failed to write search index to [$indexPath].\n");
    exit(1);
}

if (! copy($scriptSource, $scriptPath)) {
    fwrite(STDERR, "Failed to copy static search script to [$scriptPath].\n");
    exit(1);
}

$patched = injectScript($site);

echo "Static Writerside search index written to $indexPath with ".count($index)." records.\n";
echo "Injected local search script into $patched HTML files.\n";

/**
 * @return list<array{title: string, url: string, content: string}>
 */
function buildIndex(string $root): array
{
    $topicsRoot = $root.'/Writerside/topics';
    $topics = topicsFromTree($root.'/Writerside/tg.tree');
    $records = [];

    foreach ($topics as $topic) {
        $path = $topicsRoot.'/'.$topic;

        if (! is_file($path)) {
            fwrite(STDERR, "Skipping missing Writerside topic [$topic].\n");
            continue;
        }

        $markdown = (string) file_get_contents($path);
        $title = topicTitle($markdown, $topic);
        $content = markdownText($markdown);

        $records[] = [
            'title' => $title,
            'url' => preg_replace('/\.md$/', '.html', $topic) ?? $topic,
            'content' => $content,
        ];
    }

    return $records;
}

/**
 * @return list<string>
 */
function topicsFromTree(string $treePath): array
{
    $tree = simplexml_load_file($treePath);

    if ($tree === false) {
        fwrite(STDERR, "Failed to parse Writerside tree [$treePath].\n");
        exit(1);
    }

    $topics = [];
    collectTopics($tree, $topics);

    return array_values(array_unique($topics));
}

/**
 * @param  list<string>  $topics
 */
function collectTopics(SimpleXMLElement $element, array &$topics): void
{
    foreach ($element->{'toc-element'} as $tocElement) {
        $topic = (string) $tocElement['topic'];

        if ($topic !== '') {
            $topics[] = $topic;
        }

        collectTopics($tocElement, $topics);
    }
}

function topicTitle(string $markdown, string $topic): string
{
    if (preg_match('/^#\s+(.+)$/m', $markdown, $match) === 1) {
        return cleanInlineText($match[1]);
    }

    return cleanInlineText(pathinfo($topic, PATHINFO_FILENAME));
}

function markdownText(string $markdown): string
{
    $markdown = preg_replace('/^```.*$/m', ' ', $markdown) ?? $markdown;
    $markdown = preg_replace('/!\[([^\]]*)]\([^)]+\)/', '$1', $markdown) ?? $markdown;
    $markdown = preg_replace('/\[([^\]]+)]\([^)]+\)/', '$1', $markdown) ?? $markdown;
    $markdown = preg_replace('/^\s*\|?\s*:?-{3,}:?\s*(\|\s*:?-{3,}:?\s*)+\|?\s*$/m', ' ', $markdown) ?? $markdown;
    $markdown = preg_replace('/[>#*_`{}[\]()<>\-]+/', ' ', $markdown) ?? $markdown;

    return normalizeWhitespace(html_entity_decode(strip_tags($markdown), ENT_QUOTES | ENT_HTML5));
}

function cleanInlineText(string $value): string
{
    $value = preg_replace('/\[([^\]]+)]\([^)]+\)/', '$1', $value) ?? $value;
    $value = preg_replace('/[`*_{}[\]<>]+/', '', $value) ?? $value;

    return normalizeWhitespace(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5));
}

function normalizeWhitespace(string $value): string
{
    return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
}

function injectScript(string $site): int
{
    $patched = 0;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($site, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        if (! $file instanceof SplFileInfo || $file->getExtension() !== 'html') {
            continue;
        }

        $path = $file->getPathname();
        $html = (string) file_get_contents($path);

        if (str_contains($html, 'local-search.js')) {
            continue;
        }

        $relativeScript = relativePath(dirname($path), $site.'/local-search.js');
        $tag = '    <script defer src="'.$relativeScript.'"></script>'."\n";
        $updated = preg_replace('/<\/body>/i', $tag.'</body>', $html, 1);

        if (! is_string($updated) || $updated === $html) {
            fwrite(STDERR, "Could not inject search script into [$path].\n");
            continue;
        }

        if (file_put_contents($path, $updated) === false) {
            fwrite(STDERR, "Could not write updated HTML file [$path].\n");
            continue;
        }

        $patched++;
    }

    return $patched;
}

function relativePath(string $fromDirectory, string $toPath): string
{
    $from = explode('/', trim(realpath($fromDirectory) ?: $fromDirectory, '/'));
    $to = explode('/', trim(realpath($toPath) ?: $toPath, '/'));

    while ($from !== [] && $to !== [] && $from[0] === $to[0]) {
        array_shift($from);
        array_shift($to);
    }

    return str_repeat('../', count($from)).implode('/', $to);
}
