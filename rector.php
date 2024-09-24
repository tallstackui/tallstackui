<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withRules([
        InlineConstructorDefaultToPropertyRector::class,
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
    ])
    ->withSkip([
        __DIR__.'/src/routes.php',
        __DIR__.'/src/resources/views/*',
        FlipTypeControlToUseExclusiveTypeRector::class,
    ]);
