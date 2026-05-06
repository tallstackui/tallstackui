#!/usr/bin/env php
<?php

/**
 * Detects HTML-escaped Tailwind arbitrary variants in src/**\/*.php.
 *
 * Pattern: '[&amp;', '[&gt;', '[&lt;' — these appear when a test
 * assertion captures rendered Blade output verbatim (Blade HTML-escapes
 * '&', '>', '<' inside attribute values), then ships in the package
 * vendor of consumer apps. Tailwind v4 scans the package source via
 * '@source ../src/' inside css/v4.css, extracts the escaped string as
 * a class candidate, and emits a CSS nesting body with a literal
 * '&amp;' selector — which PostCSS rejects with "Unknown word &amp".
 *
 * History: caught the v3.4.0 release (Button/Group FeatureTest), shipped
 * v3.4.1 hotfix on 2026-05-04 with .gitattributes export-ignore +
 * composer exclude-from-classmap to keep tests out of the tarball.
 * This lint catches the offending pattern at PR/CI time, before release.
 *
 * Usage: php scripts/check-html-escaped-tailwind-variants.php
 */
require dirname(__DIR__).'/vendor/autoload.php';

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\table;

$root = dirname(__DIR__);
$base = $root.'/src';
$pattern = '/\[&(amp|gt|lt);/';

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base, RecursiveDirectoryIterator::SKIP_DOTS)
);

$violations = [];
$scanned = 0;

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $scanned++;
    $path = $file->getPathname();
    $lines = file($path, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $index => $line) {
        if (preg_match($pattern, $line)) {
            $relative = str_replace($root.'/', '', $path);
            $snippet = trim($line);

            if (mb_strlen($snippet) > 80) {
                $snippet = mb_substr($snippet, 0, 77).'...';
            }

            $violations[] = [$relative, (string) ($index + 1), $snippet];
        }
    }
}

note("Scanned {$scanned} PHP files under src/.");

if (empty($violations)) {
    info('No HTML-escaped Tailwind arbitrary variants detected.');

    exit(0);
}

error('Found '.count($violations).' HTML-escaped Tailwind arbitrary variant(s).');

table(
    headers: ['File', 'Line', 'Snippet'],
    rows: $violations,
);

note(<<<'TXT'
These literals break consumer Tailwind v4 / PostCSS pipelines when the package
vendor is scanned. Fix options:

  • Tests: replace '[&amp;...]' / '[&gt;...]' / '[&lt;...]' literals with
    htmlspecialchars('[&...]') so the runtime token is identical but the
    source file does not contain the escaped pattern.

  • Components: rewrite the Tailwind classes using v4 native variants
    (e.g. '*:rounded-none', '*:first:rounded-l-md', '*:not-first:-ml-px')
    so the rendered HTML never includes a '&amp;' to escape.

Context: v3.4.1 hotfix (commit 0585fdba on 3.x).
TXT);

exit(1);
