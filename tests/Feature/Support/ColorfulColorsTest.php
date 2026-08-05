<?php

use Illuminate\Support\Facades\File;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;

/**
 * Published color classes are resolved from disk, so they must be written
 * there. The namespace is unique per test and per parallel worker to keep
 * class names from being reused across them.
 */
function publish(string $component, string $scenario, string $body): void
{
    $namespace = 'App\\Colors'.(getenv('TEST_TOKEN') ?: '0').$scenario;
    $directory = app_path(str_replace('App\\', '', $namespace));

    File::ensureDirectoryExists($directory);

    $file = $directory.'/'.$component.'Colors.php';

    File::put($file, <<<PHP
    <?php

    namespace {$namespace};

    use Illuminate\\View\\Component;

    class {$component}Colors
    {
        public function colorfulColors(Component \$component): array
        {
            return {$body};
        }
    }
    PHP);

    require_once $file;

    config(['ts-ui.color_classes_namespace' => $namespace]);
}

afterEach(function () {
    Globals::reset();

    foreach (File::directories(app_path()) as $directory) {
        if (str_starts_with(basename($directory), 'Colors')) {
            File::deleteDirectory($directory);
        }
    }
});

it('can override the colorful dialog colors through a published color class', function () {
    publish('Dialog', 'Override', <<<'PHP'
    [
                'cancel' => 'text-lime-100!',
                'confirm' => ['success' => 'text-lime-900!'],
            ]
    PHP);

    TallStackUi::customize()->globals()->colorful();

    expect('<x-dialog />')->render()
        ->toContain('text-lime-100!')
        ->toContain('text-lime-900!')
        ->not->toContain('text-green-700!');
});

it('can keep the colorful dialog defaults the published class did not mention', function () {
    publish('Dialog', 'Partial', <<<'PHP'
    [
                'confirm' => ['success' => 'text-lime-900!'],
            ]
    PHP);

    TallStackUi::customize()->globals()->colorful();

    expect('<x-dialog />')->render()
        ->toContain('bg-white hover:bg-white/90')
        ->toContain('text-red-700!')
        ->toContain('text-primary-700!')
        ->toContain('hover:bg-white/20');
});

it('can override the colorful toast colors through a published color class', function () {
    publish('Toast', 'Override', <<<'PHP'
    [
                'confirm' => 'text-lime-200!',
            ]
    PHP);

    TallStackUi::customize()->globals()->colorful();

    expect('<x-toast />')->render()
        ->toContain('text-lime-200!')
        ->toContain('text-white/80')
        ->not->toContain('text-white font-bold!');
});
