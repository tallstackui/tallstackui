<?php

use Illuminate\Support\Facades\File;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-range />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-range label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-range label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with sizes', function (string $size) {
    $component = <<<'HTML'
    <x-range label="Foo bar" hint="Bar baz" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $size, $component);

    expect($component)->render()
        ->toContain('<input')
        ->toContain('Bar baz')
        ->toContain('Foo bar');
})->with(['sm', 'md', 'lg']);

it('can render with steps')
    ->expect('<x-range label="Foo bar" hint="Bar baz" step="5" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-range label="Foo bar" hint="Bar baz" color="$colors" />
    HTML;

    $colors = match ($colors) {
        'white' => 'white',
        'black' => 'black',
        default => $colors.'-500'
    };

    expect($component)->render()
        ->toContain('<input')
        ->toContain($colors);
})->with(colorsDataset());

it('forwards the boundaries to the input')
    ->expect('<x-range min="10" max="90" step="5" />')
    ->render()
    ->toContain('min="10"')
    ->toContain('max="90"')
    ->toContain('step="5"');

it('forwards the value to the input')
    ->expect('<x-range value="42" />')
    ->render()
    ->toContain('value="42"');

it('omits the boundaries when they are not given')
    ->expect('<x-range />')
    ->render()
    ->not
    ->toContain('min=')
    ->not
    ->toContain('max=')
    ->not
    ->toContain('step=')
    ->not
    ->toContain('value=');

it('cannot use tooltip without dual', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [tooltip] can only be used along with [dual].');

    expect('<x-range tooltip />')->render();
});

it('cannot use an array value without dual', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [value] can only be an array along with [dual].');

    expect('<x-range :value="[10, 20]" />')->render();
});

it('can render dual')
    ->expect('<x-range dual />')
    ->render()
    ->toContain('tallstackui_form_range_start')
    ->toContain('tallstackui_form_range_end')
    ->toContain('tallstackui_formRange');

it('can render dual with label and hint')
    ->expect('<x-range dual label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('can render dual with boundaries')
    ->expect('<x-range dual :min="10" :max="90" :step="5" />')
    ->render()
    ->toContain('min="10"')
    ->toContain('max="90"')
    ->toContain('step="5"');

it('falls back to the boundaries when dual has no value')
    ->expect('<x-range dual :min="10" :max="90" />')
    ->render()
    ->toContain('value="10"')
    ->toContain('value="90"');

it('can render dual with a value')
    ->expect('<x-range dual :value="[20, 60]" />')
    ->render()
    ->toContain('value="20"')
    ->toContain('value="60"');

it('can render dual with sizes', function (string $size) {
    expect(str_replace('{{ size }}', $size, '<x-range dual {{ size }} />'))
        ->render()
        ->toContain('tallstackui_form_range_start');
})->with(['sm', 'md', 'lg']);

it('can render dual with colors', function (string $color) {
    $expected = $color === 'black' ? 'black' : $color.'-500';

    expect("<x-range dual color=\"$color\" />")
        ->render()
        ->toContain('[&::-webkit-slider-thumb]:bg-'.$expected)
        ->toContain('[&::-moz-range-thumb]:bg-'.$expected);
})->with(colorsDataset());

it('paints the dual filled track with the component color')
    ->expect('<x-range dual color="emerald" />')
    ->render()
    ->toContain('tallstackui_form_range_progress')
    ->toContain('bg-emerald-500');

it('can render dual with tooltip')
    ->expect('<x-range dual tooltip />')
    ->render()
    ->toContain('tallstackui_form_range_tooltip_start')
    ->toContain('tallstackui_form_range_tooltip_end');

it('does not render the dual tooltip by default')
    ->expect('<x-range dual />')
    ->render()
    ->not
    ->toContain('tallstackui_form_range_tooltip_start');

it('cannot render dual with min greater than max', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [min] value must be less than the [max] value.');

    expect('<x-range dual :min="90" :max="10" />')->render();
});

it('cannot render dual with a step of zero or less', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [step] value must be greater than zero.');

    expect('<x-range dual :step="0" />')->render();
});

it('cannot render dual with a step wider than the boundaries', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [step] value must not be greater than the distance between [min] and [max].');

    expect('<x-range dual :min="0" :max="10" :step="50" />')->render();
});

it('cannot render dual with a value that is not a pair', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('must be an array with exactly two values');

    expect('<x-range dual :value="[10, 20, 30]" />')->render();
});

it('cannot render dual with a non numeric value', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('must contain only numeric values');

    expect('<x-range dual :value="[\'foo\', \'bar\']" />')->render();
});

it('cannot render dual with an inverted value', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('must be less than or equal to the second one');

    expect('<x-range dual :value="[80, 20]" />')->render();
});

it('cannot render dual with a value out of the boundaries', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('must contain values between [min] and [max]');

    expect('<x-range dual :min="0" :max="50" :value="[10, 90]" />')->render();
});

/**
 * The namespace is unique per test and per parallel worker to keep class
 * names from being reused across them.
 */
function publishRangeColors(string $scenario, string $body): void
{
    $namespace = 'App\\RangeColors'.(getenv('TEST_TOKEN') ?: '0').$scenario;
    $directory = app_path(str_replace('App\\', '', $namespace));

    File::ensureDirectoryExists($directory);

    $file = $directory.'/RangeColors.php';

    File::put($file, <<<PHP
    <?php

    namespace {$namespace};

    class RangeColors
    {
    {$body}
    }
    PHP);

    require_once $file;

    config(['ts-ui.color_classes_namespace' => $namespace]);
}

afterEach(function () {
    foreach (File::directories(app_path()) as $directory) {
        if (str_starts_with(basename($directory), 'RangeColors')) {
            File::deleteDirectory($directory);
        }
    }
});

it('can override the thumb colors through a published color class', function () {
    publishRangeColors('Thumb', <<<'PHP'
        public function thumbColors(): array
            {
                return ['red' => '[&::-webkit-slider-thumb]:bg-lime-700'];
            }
    PHP);

    expect('<x-range color="red" />')->render()
        ->toContain('bg-lime-700')
        ->not
        ->toContain('bg-red-500');
});

it('can override the filled track colors through a published color class', function () {
    publishRangeColors('Progress', <<<'PHP'
        public function progressColors(): array
            {
                return ['red' => 'bg-lime-700'];
            }
    PHP);

    expect('<x-range dual color="red" />')->render()
        ->toContain('bg-lime-700')
        ->toContain('slider-thumb]:bg-red-500');
});

it('keeps the palette defaults the published color class did not mention', function () {
    publishRangeColors('Partial', <<<'PHP'
        public function thumbColors(): array
            {
                return ['red' => '[&::-webkit-slider-thumb]:bg-lime-700'];
            }
    PHP);

    expect('<x-range dual color="red" />')->render()
        ->toContain('slider-thumb]:bg-lime-700')
        // The thumb is lime now, so any red left can only be the filled track.
        ->toContain('bg-red-500');
});

it('keeps the packaged palette when no color class was published')
    ->expect('<x-range dual color="red" />')
    ->render()
    ->toContain('[&::-webkit-slider-thumb]:bg-red-500')
    ->toContain('[&::-moz-range-thumb]:bg-red-500')
    ->toContain('bg-red-500');
