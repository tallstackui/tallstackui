#!/usr/bin/env php
<?php

/**
 * Finds customization blocks defined in Component::customization()
 * that are not referenced in the corresponding Blade template,
 * sub-views (variations), color classes, or the component PHP class itself.
 *
 * Handles:
 * - Static key access:  $customization['exact.key']
 * - Dynamic key concat: $customization['prefix.' . $variable]
 * - Sub-view delegation (variations directories)
 * - Color class personalization() calls
 * - Keys used directly in component PHP code
 *
 * Usage: php scripts/find-unused-customization-blocks.php
 */
require dirname(__DIR__).'/vendor/autoload.php';

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\table;

$root = dirname(__DIR__);
$componentsDir = $root.'/src/Components';
$viewsDir = $root.'/src/resources/views/components';
$colorsDir = $root.'/src/Support/Colors/Components';

// ── File Discovery ───────────────────────────────────────────

function findComponentFiles(string $dir): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getFilename() === 'Component.php') {
            $files[] = $file->getPathname();
        }
    }

    sort($files);

    return $files;
}

function findRelatedBladeFiles(string $mainBladePath, string $viewsDir): array
{
    $files = [$mainBladePath];
    $mainContent = file_get_contents($mainBladePath);

    if (strpos($mainContent, ':$customization') === false) {
        return $files;
    }

    preg_match_all('/component=["\']ts-ui::([^"\']+)["\']/', $mainContent, $viewRefs);

    foreach ($viewRefs[1] ?? [] as $viewRef) {
        if (strpos($viewRef, '{{') !== false) {
            $staticPart = preg_replace('/\{\{.*?\}\}.*$/', '', $viewRef);
            $dirPath = $viewsDir.'/'.str_replace('.', '/', rtrim($staticPart, '.'));

            if (is_dir($dirPath)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    $path = $file->getPathname();

                    if (str_ends_with($path, '.blade.php') && ! in_array($path, $files)) {
                        $files[] = $path;
                    }
                }
            }
        } else {
            $path = $viewsDir.'/'.str_replace('.', '/', $viewRef).'.blade.php';

            if (file_exists($path) && ! in_array($path, $files)) {
                $files[] = $path;
            }
        }
    }

    return $files;
}

// ── Parsing ──────────────────────────────────────────────────

function extractMethodBody(string $source, string $methodName): ?string
{
    // PHP's tokenizer correctly handles strings with embedded opposite quotes,
    // escaped sequences, comments and heredocs — eliminating the entire class
    // of bugs the previous char-by-char detector was vulnerable to.
    $tokens = @token_get_all($source);
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        if (! is_array($tokens[$i]) || $tokens[$i][0] !== T_FUNCTION) {
            continue;
        }

        $j = $i + 1;
        while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
            $j++;
        }

        if ($j >= $count
            || ! is_array($tokens[$j])
            || $tokens[$j][0] !== T_STRING
            || $tokens[$j][1] !== $methodName) {
            continue;
        }

        $bodyStart = null;
        $parenDepth = 0;

        for ($k = $j + 1; $k < $count; $k++) {
            $token = $tokens[$k];
            $text = is_array($token) ? $token[1] : $token;

            if ($text === '(') {
                $parenDepth++;
            } elseif ($text === ')') {
                $parenDepth--;
            } elseif ($text === '{' && $parenDepth === 0) {
                $bodyStart = $k + 1;
                break;
            }
        }

        if ($bodyStart === null) {
            continue;
        }

        $bodyDepth = 1;
        $bodyParts = [];

        for ($m = $bodyStart; $m < $count; $m++) {
            $token = $tokens[$m];
            $text = is_array($token) ? $token[1] : $token;

            if ($text === '{') {
                $bodyDepth++;
            } elseif ($text === '}') {
                $bodyDepth--;

                if ($bodyDepth === 0) {
                    return implode('', $bodyParts);
                }
            }

            $bodyParts[] = $text;
        }
    }

    return null;
}

function parseCustomizationKeys(string $methodBody): array
{
    $keys = [];
    $hasSpread = false;
    $keyStack = [];
    $depthStack = [];
    $bracketDepth = 0;

    $tokens = token_get_all('<?php '.$methodBody);
    $len = count($tokens);

    for ($i = 0; $i < $len; $i++) {
        $token = $tokens[$i];

        if (is_array($token) && $token[0] === T_ELLIPSIS) {
            $hasSpread = true;

            continue;
        }

        if ($token === '[') {
            $bracketDepth++;

            continue;
        }

        if ($token === ']') {
            if (! empty($depthStack) && end($depthStack) === $bracketDepth) {
                array_pop($keyStack);
                array_pop($depthStack);
            }
            $bracketDepth--;

            continue;
        }

        if (is_array($token) && $token[0] === T_CONSTANT_ENCAPSED_STRING) {
            $keyValue = trim($token[1], "'\"");

            $j = $i + 1;
            while ($j < $len && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
                $j++;
            }

            if ($j >= $len || ! is_array($tokens[$j]) || $tokens[$j][0] !== T_DOUBLE_ARROW) {
                continue;
            }

            $k = $j + 1;
            while ($k < $len && is_array($tokens[$k]) && $tokens[$k][0] === T_WHITESPACE) {
                $k++;
            }

            if ($k < $len && $tokens[$k] === '[') {
                $keyStack[] = $keyValue;
                $depthStack[] = $bracketDepth + 1;
                $i = $k - 1;

                continue;
            }

            $path = array_merge($keyStack, [$keyValue]);
            $keys[] = implode('.', $path);
        }
    }

    return ['keys' => $keys, 'hasSpread' => $hasSpread];
}

// ── View Resolution ──────────────────────────────────────────

/**
 * Every view literal the blade() method can return. A single-literal regex
 * missed the ternary form — view($this->skeletonized() ? 'a' : 'b') — and the
 * component was skipped while still being counted as scanned.
 *
 * @return array<int, string>
 */
function extractBladeViewNames(string $source): array
{
    $body = extractMethodBody($source, 'blade');

    if ($body === null) {
        return [];
    }

    preg_match_all("/'((?:ts-ui|tallstack-ui)::[^']+)'/", $body, $matches);

    return array_values(array_unique($matches[1] ?? []));
}

function viewNameToPath(string $viewName, string $viewsDir): ?string
{
    $prefixes = ['ts-ui::components.', 'tallstack-ui::components.'];

    foreach ($prefixes as $prefix) {
        if (str_starts_with($viewName, $prefix)) {
            $relative = substr($viewName, strlen($prefix));
            $path = $viewsDir.'/'.str_replace('.', '/', $relative).'.blade.php';

            return file_exists($path) ? $path : null;
        }
    }

    return null;
}

// ── Key Usage Detection ──────────────────────────────────────

function findPersonalizeUsage(string $content): array
{
    // Static: $customization['exact.key']
    preg_match_all('/\$customization\[[\'"]([^\'"]+)[\'"]\]/', $content, $staticMatches);

    // Dynamic concatenation: $customization['prefix.' . $var] — tolerates any
    // whitespace around the concatenation dot.
    preg_match_all('/\$customization\[[\'"]([^\'"]+\.)[\'"]\s*\./', $content, $dynamicMatches);

    return [
        'static' => array_unique($staticMatches[1] ?? []),
        'prefixes' => array_unique($dynamicMatches[1] ?? []),
    ];
}

function findColorClassFile(string $phpSource, string $colorsDir): ?string
{
    if (preg_match('/#\[ColorsThroughOf\((\w+)::class\)\]/', $phpSource, $match)) {
        $file = $colorsDir.'/'.$match[1].'.php';

        return file_exists($file) ? $file : null;
    }

    return null;
}

function findColorPersonalizationKeys(string $colorContent): array
{
    preg_match_all('/personalization\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $colorContent, $matches);

    return array_unique($matches[1] ?? []);
}

function isKeyUsed(string $key, array $staticKeys, array $dynamicPrefixes): bool
{
    if (in_array($key, $staticKeys, true)) {
        return true;
    }

    foreach ($dynamicPrefixes as $prefix) {
        if (str_starts_with($key, $prefix)) {
            return true;
        }
    }

    return false;
}

// ── Main ─────────────────────────────────────────────────────

$componentFiles = findComponentFiles($componentsDir);
$totalUnused = 0;
$totalComponents = 0;
$totalKeys = 0;
$results = [];
$unresolved = [];

foreach ($componentFiles as $componentFile) {
    $source = file_get_contents($componentFile);

    $methodBody = extractMethodBody($source, 'customization');

    if ($methodBody === null) {
        continue;
    }

    $parsed = parseCustomizationKeys($methodBody);
    $definedKeys = $parsed['keys'];
    $hasSpread = $parsed['hasSpread'];

    if (empty($definedKeys)) {
        $totalComponents++;

        continue;
    }

    $viewNames = extractBladeViewNames($source);

    $bladePaths = array_values(array_filter(array_map(
        fn (string $viewName): ?string => viewNameToPath($viewName, $viewsDir),
        $viewNames,
    )));

    if ($bladePaths === []) {
        $unresolved[] = str_replace($root.'/', '', $componentFile);

        continue;
    }

    $totalComponents++;
    $totalKeys += count($definedKeys);

    $bladePath = $bladePaths[0];

    // Collect all static keys and dynamic prefixes from all sources
    $allStaticKeys = [];
    $allDynamicPrefixes = [];

    // 1. Scan the component PHP file itself (e.g., Reaction uses keys in content() method)
    $phpUsage = findPersonalizeUsage($source);
    $allStaticKeys = array_merge($allStaticKeys, $phpUsage['static']);
    $allDynamicPrefixes = array_merge($allDynamicPrefixes, $phpUsage['prefixes']);

    // 2. Scan every blade the component can return, plus their sub-views
    $bladeFiles = [];

    foreach ($bladePaths as $path) {
        $bladeFiles = array_merge($bladeFiles, findRelatedBladeFiles($path, $viewsDir));
    }

    $bladeFiles = array_values(array_unique($bladeFiles));

    foreach ($bladeFiles as $bladeFile) {
        $bladeContent = file_get_contents($bladeFile);
        $bladeUsage = findPersonalizeUsage($bladeContent);
        $allStaticKeys = array_merge($allStaticKeys, $bladeUsage['static']);
        $allDynamicPrefixes = array_merge($allDynamicPrefixes, $bladeUsage['prefixes']);
    }

    // 3. Scan associated color class
    $colorFile = findColorClassFile($source, $colorsDir);

    if ($colorFile !== null) {
        $colorContent = file_get_contents($colorFile);
        $colorKeys = findColorPersonalizationKeys($colorContent);
        $allStaticKeys = array_merge($allStaticKeys, $colorKeys);
    }

    $allStaticKeys = array_unique($allStaticKeys);
    $allDynamicPrefixes = array_unique($allDynamicPrefixes);

    // Find unused keys
    $unusedKeys = [];

    foreach ($definedKeys as $key) {
        if (! isKeyUsed($key, $allStaticKeys, $allDynamicPrefixes)) {
            $unusedKeys[] = $key;
        }
    }

    if (! empty($unusedKeys)) {
        $scannedFiles = array_map(
            fn (string $f) => str_replace($root.'/', '', $f),
            $bladeFiles,
        );

        if ($colorFile !== null) {
            $scannedFiles[] = str_replace($root.'/', '', $colorFile);
        }

        $results[] = [
            'component' => str_replace($root.'/', '', $componentFile),
            'blade' => str_replace($root.'/', '', $bladePath),
            'scanned' => $scannedFiles,
            'unused' => $unusedKeys,
            'hasSpread' => $hasSpread,
            'dynamicPrefixes' => $allDynamicPrefixes,
        ];
        $totalUnused += count($unusedKeys);
    }
}

// ── Output ───────────────────────────────────────────────────

note("Scanned {$totalComponents} components, {$totalKeys} customization keys.");

// A silent skip reads as coverage, which is the failure this check exists to avoid.
if (! empty($unresolved)) {
    error('Could not resolve the blade view of '.count($unresolved).' component(s), so their blocks were never checked.');

    foreach ($unresolved as $component) {
        note("  {$component}");
    }

    exit(1);
}

if (! empty($results)) {
    foreach ($results as $result) {
        if ($result['hasSpread'] === true) {
            note("Note: {$result['component']} spreads another array into its customization, so some blocks may be reported as unused while being defined elsewhere.");
        }
    }
}

if (empty($results)) {
    info('All customization blocks are in use!');

    exit(0);
}

error("Found {$totalUnused} unused customization block(s).");

$rows = [];

foreach ($results as $result) {
    $component = str_replace('src/Components/', '', $result['component']);
    $component = str_replace('/Component.php', '', $component);

    foreach ($result['unused'] as $key) {
        $rows[] = [$component, $key, $result['blade']];
    }
}

table(
    headers: ['Component', 'Unused Block', 'Blade'],
    rows: $rows,
);

exit(1);
