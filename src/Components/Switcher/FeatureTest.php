<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use TallStackUi\Components\Switcher\Component;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    Globals::reset();
});

it('can render')
    ->expect('<x-switcher on="Following" off="Follow" />')
    ->render()
    ->toContain('tallstackui_switcher')
    ->toContain('type="checkbox"')
    ->toContain('role="switch"')
    ->toContain('Following')
    ->toContain('Follow');

it('can render only icons')
    ->expect('<x-switcher icon-when-on="sun" icon-when-off="moon" />')
    ->render()
    ->toContain('<svg')
    ->toContain('h-6 w-6');

it('can render text with icons')
    ->expect('<x-switcher on="Pause" off="Play" icon-when-on="pause" icon-when-off="play" />')
    ->render()
    ->toContain('<svg')
    ->toContain('Pause')
    ->toContain('Play');

it('can render text without icons')
    ->expect('<x-switcher on="Yes" off="No" />')
    ->render()
    ->not->toContain('<svg');

it('can render slots', function () {
    $component = <<<'HTML'
    <x-switcher>
        <x-slot:on>
            <b>Enabled</b>
        </x-slot:on>
        <x-slot:off>
            <i>Disabled</i>
        </x-slot:off>
    </x-switcher>
    HTML;

    expect($component)->render()
        ->toContain('<b>Enabled</b>')
        ->toContain('<i>Disabled</i>');
});

it('can mix a state as text and the other as icon')
    ->expect('<x-switcher on="Done" icon-when-off="check" />')
    ->render()
    ->toContain('Done')
    ->toContain('<svg');

it('can render size variations', function (string $size, string $text, string $icon) {
    expect("<x-switcher {$size} on=\"Foo\" off=\"Bar\" icon-when-on=\"sun\" icon-when-off=\"moon\" />")->render()
        ->toContain($text)
        ->toContain($icon);
})->with([
    'xs' => ['xs', 'text-xs', 'h-4 w-4'],
    'sm' => ['sm', 'text-sm', 'h-5 w-5'],
    'md' => ['md', 'text-base', 'h-6 w-6'],
    'lg' => ['lg', 'text-lg', 'h-7 w-7'],
]);

it('can render default size as md')
    ->expect('<x-switcher on="Foo" off="Bar" />')
    ->render()
    ->toContain('text-base')
    ->not->toContain('text-xs')
    ->not->toContain('text-lg');

it('can render the icon on the left by default')
    ->expect('<x-switcher on="Foo" off="Bar" icon-when-on="sun" icon-when-off="moon" />')
    ->render()
    ->not->toContain('flex-row-reverse');

it('can render the icon on the right')
    ->expect('<x-switcher on="Foo" off="Bar" icon-when-on="sun" icon-when-off="moon" position="right" />')
    ->render()
    ->toContain('flex-row-reverse');

it('cannot render a color by default')
    ->expect('<x-switcher on="Foo" off="Bar" />')
    ->render()
    ->not->toMatch('/text-(black|[a-z]+-500)/');

it('can render a color for each state', function () {
    $html = Blade::render('<x-switcher on="Foo" off="Bar" color-when-on="red" color-when-off="green" />');

    $on = str($html)->after('tallstackui_switcher_on')->before('</span>')->value();
    $off = str($html)->after('tallstackui_switcher_off')->before('</span>')->value();

    expect($on)->toContain('text-red-500')
        ->not->toContain('text-green-500')
        ->and($off)->toContain('text-green-500')
        ->not->toContain('text-red-500');
});

it('can render a color for a single state', function (string $attribute, string $painted, string $inherited) {
    $html = Blade::render("<x-switcher on=\"Foo\" off=\"Bar\" {$attribute}=\"red\" />");

    expect(str($html)->after("tallstackui_switcher_{$painted}")->before('</span>')->value())
        ->toContain('text-red-500')
        ->and(str($html)->after("tallstackui_switcher_{$inherited}")->before('</span>')->value())
        ->not->toMatch('/text-(black|[a-z]+-500)/');
})->with([
    'on' => ['color-when-on', 'on', 'off'],
    'off' => ['color-when-off', 'off', 'on'],
]);

it('can render all the colors', function (string $color) {
    $class = $color === 'black' ? 'text-black' : "text-{$color}-500";

    expect("<x-switcher on=\"Foo\" off=\"Bar\" color-when-on=\"{$color}\" color-when-off=\"{$color}\" />")->render()
        ->toContain($class);
})->with(colorsDataset());

it('cannot render a color that does not exist')
    ->expect('<x-switcher on="Foo" off="Bar" color-when-on="foo" />')
    ->render()
    ->not->toMatch('/text-(black|[a-z]+-500)/');

it('can render fading by default')
    ->expect('<x-switcher on="Foo" off="Bar" />')
    ->render()
    ->toContain('peer-checked:opacity-100')
    ->not->toContain('rotate-45')
    ->not->toContain('rotate-y-180');

it('can render rotating')
    ->expect('<x-switcher rotate on="Foo" off="Bar" />')
    ->render()
    ->toContain('-rotate-45')
    ->toContain('peer-checked:rotate-45')
    ->not->toContain('rotate-y-180');

it('can render flipping')
    ->expect('<x-switcher flip on="Foo" off="Bar" />')
    ->render()
    ->toContain('rotate-y-180')
    ->toContain('backface-hidden')
    ->toContain('perspective-near')
    ->not->toContain('rotate-45');

it('cannot use rotate with flip', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [rotate] and [flip] cannot be used together.');

    expect('<x-switcher rotate flip on="Foo" off="Bar" />')->render();
});

it('cannot render without the on state', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [on] state requires a text, a slot or an icon.');

    expect('<x-switcher off="Bar" />')->render();
});

it('cannot render without the off state', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [off] state requires a text, a slot or an icon.');

    expect('<x-switcher icon-when-on="sun" />')->render();
});

it('can send the class to the wrapper and the rest to the checkbox', function () {
    $html = Blade::render('<x-switcher class="text-red-500" name="notify" value="yes" checked on="Foo" off="Bar" />');

    $label = str($html)->before('<input')->value();
    $input = str($html)->after('<input')->before('>')->value();

    expect($label)->toContain('text-red-500')
        ->and($input)->not->toContain('text-red-500')
        ->toContain('name="notify"')
        ->toContain('value="yes"')
        ->toContain('checked');
});

it('can render checked from the livewire property', function (bool $active) {
    app()->register(LivewireServiceProvider::class);

    $component = new class extends LivewireComponent
    {
        public bool $active = false;

        public function render(): string
        {
            return <<<'HTML'
            <div>
                <x-switcher wire:model="active" on="Foo" off="Bar" />
            </div>
            HTML;
        }
    };

    $html = Livewire::test($component, ['active' => $active])->html();

    expect(str_contains(str($html)->after('<input')->before('<span')->value(), ' checked '))->toBe($active);
})->with([
    'truthy' => [true],
    'falsy' => [false],
]);

it('cannot render checked outside livewire')
    ->expect('<x-switcher name="active" on="Foo" off="Bar" />')
    ->render()
    ->not->toContain(' checked ');

it('can forward the switch event to the checkbox', function () {
    $html = Blade::render('<x-switcher x-on:switch="foo = $event.detail.value" on="Foo" off="Bar" />');

    expect(str($html)->after('<input')->before('<span')->value())
        ->toContain("new CustomEvent('switch'")
        ->toContain('x-on:switch="foo = $event.detail.value"');
});

it('can render disabled', function () {
    $html = Blade::render('<x-switcher disabled on="Foo" off="Bar" />');

    expect($html)->toContain('cursor-not-allowed!')
        ->and(str($html)->after('<input')->before('<span')->value())
        ->toContain('disabled')
        ->not->toContain('aria-readonly');
});

it('can render readonly')
    ->expect('<x-switcher readonly on="Foo" off="Bar" />')
    ->render()
    ->toContain('cursor-not-allowed!')
    ->toContain('x-on:click.prevent')
    ->toContain('aria-readonly="true"');

it('cannot paint the lock when interactive')
    ->expect('<x-switcher on="Foo" off="Bar" />')
    ->render()
    ->not->toContain('cursor-not-allowed!')
    ->not->toContain('aria-readonly');

it('can remove transitions when flash is global', function () {
    $component = '<x-switcher on="Foo" off="Bar" />';

    expect($component)->render()->toContain('transition-all');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('transition-all');
});

it('can remove transitions when flash targets the component', function () {
    TallStackUi::customize()->globals()->flash(only: [Component::class]);

    expect('<x-switcher rotate on="Foo" off="Bar" />')->render()
        ->not->toContain('transition-all');
});

it('can customize the blocks', function () {
    TallStackUi::customize()
        ->switcher()
        ->block('on.fade')
        ->append('text-green-500');

    expect('<x-switcher on="Foo" off="Bar" />')->render()
        ->toContain('text-green-500');
});

it('can customize the blocks through a scope', function () {
    TallStackUi::customize('switcher', scope: 'danger')
        ->block('wrapper.base')
        ->append('text-red-700');

    expect('<x-switcher on="Foo" off="Bar" />')->render()
        ->not->toContain('text-red-700');

    expect('<x-switcher scope="danger" on="Foo" off="Bar" />')->render()
        ->toContain('text-red-700');
});
