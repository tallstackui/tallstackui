<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Tooltip\Component;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    Globals::reset();
});

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-tooltip text="Foo bar" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    expect($component)->render()
        ->toContain('Foo bar', $class);
})->with([
    fn () => ['xs' => 'h-4 w-4'],
    fn () => ['sm' => 'h-5 w-5'],
    fn () => ['md' => 'h-6 w-6'],
    fn () => ['lg' => 'h-7 w-7'],
]);

it('can render positioned', function (string $position) {
    $component = <<<HTML
    <x-tooltip text="Foo bar" position="$position" />
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
})->with(['top', 'bottom', 'left', 'right']);

it('cannot use bad positions', function (string $position) {
    $this->expectException(ViewException::class);

    $component = <<<HTML
    <x-tooltip text="Foo bar" position="$position" />
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
})->with(['foo', 'bar', 'baz']);

it('cannot emit the flash data attribute by default', function () {
    expect(TallStackUi::directives()->script())->not->toContain('data-tsui-tooltip-flash');
});

it('can emit the flash data attribute when the global is enabled', function () {
    TallStackUi::customize()->globals()->flash();

    expect(TallStackUi::directives()->script())->toContain('data-tsui-tooltip-flash="true"');
});

it('cannot emit the flash data attribute when the global excludes the component', function () {
    TallStackUi::customize()->globals()->flash(except: [Component::class]);

    expect(TallStackUi::directives()->script())->not->toContain('data-tsui-tooltip-flash');
});
