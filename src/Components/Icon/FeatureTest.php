<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Icon\Component;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.icon.1.size', 'md');

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render')
    ->expect('<x-icon icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render solid')
    ->expect('<x-icon icon="users" solid />')
    ->render()
    ->toContain('<svg');

it('can render outline')
    ->expect('<x-icon icon="users" outline />')
    ->render()
    ->toContain('<svg');

it('can render with error')
    ->expect('<x-icon icon="users" outline error />')
    ->render()
    ->toContain('text-red-500');

it('can render using the medium size as the default')
    ->expect('<x-icon icon="users" />')
    ->render()
    ->toContain('h-5 w-5');

it('can render every size', function (string $size, string $classes) {
    expect("<x-icon icon=\"users\" {$size} />")->render()->toContain($classes);
})->with([
    ['xs', 'h-3 w-3'],
    ['sm', 'h-4 w-4'],
    ['md', 'h-5 w-5'],
    ['lg', 'h-6 w-6'],
    ['xl', 'h-7 w-7'],
    ['2xl', 'h-8 w-8'],
    ['3xl', 'h-10 w-10'],
    ['4xl', 'h-12 w-12'],
    ['5xl', 'h-14 w-14'],
    ['6xl', 'h-16 w-16'],
    ['7xl', 'h-20 w-20'],
]);

it('can render with a color')
    ->expect('<x-icon icon="users" red />')
    ->render()
    ->toContain('text-red-600');

it('can render with a size and a color together')
    ->expect('<x-icon icon="users" 2xl secondary />')
    ->render()
    ->toContain('h-8 w-8', 'text-secondary-600');

it('does not leak the shorthands into the svg')
    ->expect('<x-icon icon="users" 2xl red />')
    ->render()
    ->not->toContain('2xl', 'red="red"');

it('can render without any color by default')
    ->expect('<x-icon icon="users" />')
    ->render()
    ->not->toContain('text-primary-600');

it('cannot apply the size when the class is declared')
    ->expect('<x-icon icon="users" 2xl class="size-4" />')
    ->render()
    ->toContain('size-4')
    ->not->toContain('h-8 w-8');

it('cannot apply the color when the class is declared')
    ->expect('<x-icon icon="users" red class="text-blue-500" />')
    ->render()
    ->toContain('text-blue-500')
    ->not->toContain('text-red-600');

it('cannot apply the shorthands when the class is empty')
    ->expect('<x-icon icon="users" 2xl red class="" />')
    ->render()
    ->not->toContain('h-8 w-8', 'text-red-600');

it('cannot apply the default size when the class is declared')
    ->expect('<x-icon icon="users" class="mr-2" />')
    ->render()
    ->toContain('mr-2')
    ->not->toContain('h-5 w-5');

it('can use the error taking precedence over the color')
    ->expect('<x-icon icon="users" error red />')
    ->render()
    ->toContain('text-red-500')
    ->not->toContain('text-red-600');

it('cannot use more than one size at a time', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Only one size can be used at a time');

    expect('<x-icon icon="users" xs 2xl />')->render();
});

it('cannot use more than one color at a time', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('Only one color can be used at a time');

    expect('<x-icon icon="users" red blue />')->render();
});

it('can use the global size configuration', function () {
    config()->set('ts-ui.components.icon.1.size', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-icon icon="users" />')->render()->toContain('h-6 w-6');
});

it('cannot use an invalid global size configuration', function () {
    config()->set('ts-ui.components.icon.1.size', 'foo');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [size] must be one of');

    expect('<x-icon icon="users" />')->render();
});

it('can be softly customized', function () {
    TallStackUi::customize()->icon()->block('sizes.md', 'foo-bar-baz');

    expect('<x-icon icon="users" />')->render()->toContain('foo-bar-baz');
});

it('can be softly customized using scope', function () {
    TallStackUi::customize('icon', scope: 'huge')->block('sizes.md', 'foo-bar-baz');

    expect('<x-icon icon="users" scope="huge" />')->render()->toContain('foo-bar-baz');

    expect('<x-icon icon="users" />')->render()->not->toContain('foo-bar-baz');
});
