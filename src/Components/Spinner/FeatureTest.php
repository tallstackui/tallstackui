<?php

use Illuminate\Support\Facades\File;
use Illuminate\View\ViewException;
use TallStackUi\Components\Spinner\Component;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.spinner.1.type', 'ring');
    config()->set('ts-ui.components.spinner.1.size', 'md');

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render')
    ->expect('<x-spinner />')
    ->render()
    ->toContain('dusk="spinner-ring"')
    ->toContain('animate-spin')
    ->toContain('role="status"');

it('can render the ring as the default variant')
    ->expect('<x-spinner />')
    ->render()
    ->toContain('border-t-transparent')
    ->not->toContain('dusk="spinner-bars"');

it('has a view for every variant', function (string $type) {
    expect(view()->exists('ts-ui::components.spinner.types.'.$type))->toBeTrue();
})->with(Component::TYPES);

it('has a variant flag for every view', function () {
    $views = collect(File::files(__DIR__.'/../../resources/views/components/spinner/types'))
        ->map(fn (SplFileInfo $file): string => str_replace('.blade.php', '', $file->getFilename()))
        ->sort()
        ->values()
        ->all();

    expect($views)->toBe(collect(Component::TYPES)->sort()->values()->all());
});

it('can render variants', function (array $variant) {
    $flag = array_key_first($variant);

    $component = str_replace('{{ variant }}', $flag, '<x-spinner {{ variant }} text="Loading" />');

    expect($component)->render()->toContain($variant[$flag]);
})->with([
    fn () => ['ring' => 'dusk="spinner-ring"'],
    fn () => ['throbber' => 'dusk="spinner-throbber"'],
    fn () => ['gradient' => 'dusk="spinner-gradient"'],
    fn () => ['ping' => 'dusk="spinner-ping"'],
    fn () => ['dots' => 'dusk="spinner-dots"'],
    fn () => ['pulse' => 'dusk="spinner-pulse"'],
    fn () => ['typing' => 'dusk="spinner-typing"'],
    fn () => ['bars' => 'dusk="spinner-bars"'],
    fn () => ['wave' => 'dusk="spinner-wave"'],
    fn () => ['shimmer' => 'dusk="spinner-shimmer"'],
    fn () => ['caret' => 'dusk="spinner-caret"'],
    fn () => ['terminal' => 'dusk="spinner-terminal"'],
    fn () => ['thinking' => 'dusk="spinner-thinking"'],
]);

it('can render the animation of each variant', function (array $variant) {
    $flag = array_key_first($variant);

    $component = str_replace('{{ variant }}', $flag, '<x-spinner {{ variant }} text="Loading" />');

    expect($component)->render()->toContain($variant[$flag]);
})->with([
    fn () => ['ring' => 'animate-spin'],
    fn () => ['throbber' => 'animate-spin'],
    fn () => ['gradient' => 'animate-spin'],
    fn () => ['ping' => 'animate-ping'],
    fn () => ['dots' => 'animate-spinner-dots'],
    fn () => ['pulse' => 'animate-spinner-pulse'],
    fn () => ['typing' => 'animate-spinner-typing'],
    fn () => ['bars' => 'animate-spinner-bars'],
    fn () => ['wave' => 'animate-spinner-wave'],
    fn () => ['shimmer' => 'animate-spinner-shimmer'],
    fn () => ['caret' => 'animate-spinner-caret'],
    fn () => ['terminal' => 'animate-spinner-caret'],
]);

it('can render default size as md')
    ->expect('<x-spinner />')
    ->render()
    ->toContain('size-6');

it('can render size variations', function (array $size) {
    $key = array_key_first($size);

    $component = str_replace('{{ size }}', $key, '<x-spinner {{ size }} />');

    expect($component)->render()->toContain($size[$key]);
})->with([
    fn () => ['xs' => 'size-4'],
    fn () => ['sm' => 'size-5'],
    fn () => ['md' => 'size-6'],
    fn () => ['lg' => 'size-8'],
]);

it('can render size variations for the bars', function (array $size) {
    $key = array_key_first($size);

    $component = str_replace('{{ size }}', $key, '<x-spinner bars {{ size }} />');

    expect($component)->render()->toContain($size[$key]);
})->with([
    fn () => ['xs' => 'h-3 w-1'],
    fn () => ['sm' => 'h-4 w-1'],
    fn () => ['md' => 'h-5 w-1.5'],
    fn () => ['lg' => 'h-6 w-2'],
]);

it('can render default color as primary')
    ->expect('<x-spinner />')
    ->render()
    ->toContain('text-primary-600');

it('can render colors', function (string $color) {
    $component = str_replace('{{ color }}', $color, '<x-spinner color="{{ color }}" />');

    expect($component)->render()->toContain('text-'.$color.'-600');
})->with(['red', 'green', 'blue', 'amber', 'teal', 'rose']);

it('can render black color')
    ->expect('<x-spinner color="black" />')
    ->render()
    ->toContain('text-black');

it('can render with text')
    ->expect('<x-spinner text="Sending the file" />')
    ->render()
    ->toContain('Sending the file')
    ->toContain('font-medium')
    ->not->toContain('sr-only');

it('can render with slot')
    ->expect('<x-spinner>Sending the file</x-spinner>')
    ->render()
    ->toContain('Sending the file')
    ->not->toContain('sr-only');

it('can render the screen reader fallback when there is no text')
    ->expect('<x-spinner />')
    ->render()
    ->toContain('sr-only')
    ->toContain('Loading...');

it('can render the stagger delays')
    ->expect('<x-spinner wave />')
    ->render()
    ->toContain('[animation-delay:120ms]')
    ->toContain('[animation-delay:480ms]');

it('can render the 12 throbber segments')
    ->expect('<x-spinner throbber />')
    ->render()
    ->toContain('rotate(0 12 12)')
    ->toContain('rotate(330 12 12)');

it('can render the thinking variant with the translated label')
    ->expect('<x-spinner thinking />')
    ->render()
    ->toContain('Thinking...')
    ->toContain('tallstackui_spinner')
    ->toContain('⠋');

it('can render the thinking variant with a custom label')
    ->expect('<x-spinner thinking text="Analyzing" />')
    ->render()
    ->toContain('Analyzing')
    ->not->toContain('Thinking...');

it('can render the thinking variant without any label')
    ->expect('<x-spinner thinking :text="false" />')
    ->render()
    ->toContain('tallstackui_spinner')
    ->toContain('sr-only')
    ->not->toContain('Thinking...');

it('can render the thinking variant with a custom interval')
    ->expect('<x-spinner thinking :interval="250" />')
    ->render()
    ->toContain('250');

it('can render the terminal variant without text')
    ->expect('<x-spinner terminal />')
    ->render()
    ->toContain('dusk="spinner-terminal"')
    ->toContain('&gt;');

it('cannot use more than one variant at a time', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Only one variant can be used at a time');

    expect('<x-spinner wave bars />')->render();
});

it('cannot use the shimmer variant without any text', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('animates its own text');

    expect('<x-spinner shimmer />')->render();
});

it('cannot use the caret variant without any text', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('animates its own text');

    expect('<x-spinner caret />')->render();
});

it('can use the shimmer variant with the slot', function () {
    expect('<x-spinner shimmer>Loading</x-spinner>')
        ->render()
        ->toContain('dusk="spinner-shimmer"')
        ->toContain('Loading');
});

it('cannot use an invalid interval', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [interval] must be greater than 0');

    expect('<x-spinner thinking :interval="0" />')->render();
});

it('can be softly customized', function () {
    TallStackUi::customize()->spinner()->block('bars.bar', 'foo-bar-baz');

    expect('<x-spinner bars />')->render()->toContain('foo-bar-baz');
});

it('can be softly customized through a scope', function () {
    TallStackUi::customize('spinner', scope: 'chat')->block('typing.dot', 'foo-bar-baz');

    expect('<x-spinner typing scope="chat" />')->render()->toContain('foo-bar-baz');

    expect('<x-spinner typing />')->render()->not->toContain('foo-bar-baz');
});

it('can use the global type configuration', function () {
    config()->set('ts-ui.components.spinner.1.type', 'bars');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-spinner />')->render()->toContain('dusk="spinner-bars"');
});

it('can use the global size configuration', function () {
    config()->set('ts-ui.components.spinner.1.size', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-spinner />')->render()->toContain('size-8');
});

it('cannot use an invalid global type configuration', function () {
    config()->set('ts-ui.components.spinner.1.type', 'foo');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [type] must be one of');

    expect('<x-spinner />')->render();
});

it('cannot use an invalid global size configuration', function () {
    config()->set('ts-ui.components.spinner.1.size', 'foo');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [size] must be one of');

    expect('<x-spinner />')->render();
});
