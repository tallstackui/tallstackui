<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Avatar\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-avatar label="Lorem" />')
    ->render()
    ->toContain('Lorem');

it('can render the sizes through the shorthands', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" {$size} />")->render()->toContain($expected);
})->with([
    'xs' => ['xs', 'w-6 h-6'],
    'sm' => ['sm', 'w-8 h-8'],
    'md' => ['md', 'w-12 h-12'],
    'lg' => ['lg', 'w-14 h-14'],
    'xl' => ['xl', 'w-16 h-16'],
    '2xl' => ['2xl', 'w-20 h-20'],
    '3xl' => ['3xl', 'w-24 h-24'],
    '4xl' => ['4xl', 'w-28 h-28'],
    '5xl' => ['5xl', 'w-32 h-32'],
    '6xl' => ['6xl', 'w-36 h-36'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can render the shorthands through the bound form', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" :{$size}=\"true\" />")->render()->toContain($expected);
})->with([
    'lg' => ['lg', 'w-14 h-14'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can fall back to the default when the bound shorthand is false', function (string $size) {
    expect("<x-avatar label=\"Lorem\" :{$size}=\"false\" />")->render()->toContain('w-12 h-12');
})->with(['lg', '7xl']);

it('cannot leak the shorthand into the rendered tag', function (string $size) {
    expect("<x-avatar text=\"AJ\" {$size} />")->render()->not->toContain($size.'=');
})->with(['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl']);

it('cannot use more than one size at a time', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar label="Lorem" sm 7xl />')->render();
});

it('can render md by default')
    ->expect('<x-avatar label="Lorem" />')
    ->render()
    ->toContain('w-12 h-12');

it('can render the sizes through the size attribute', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" size=\"{$size}\" />")->render()->toContain($expected);
})->with([
    'xs' => ['xs', 'w-6 h-6'],
    'sm' => ['sm', 'w-8 h-8'],
    'md' => ['md', 'w-12 h-12'],
    'lg' => ['lg', 'w-14 h-14'],
    'xl' => ['xl', 'w-16 h-16'],
    '2xl' => ['2xl', 'w-20 h-20'],
    '3xl' => ['3xl', 'w-24 h-24'],
    '4xl' => ['4xl', 'w-28 h-28'],
    '5xl' => ['5xl', 'w-32 h-32'],
    '6xl' => ['6xl', 'w-36 h-36'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can render the presence dot scaled to the size', function (string $size, string $expected) {
    expect("<x-avatar text=\"AJ\" size=\"{$size}\" presence />")->render()->toContain($expected);
})->with([
    'xl' => ['xl', 'h-4 w-4'],
    '2xl' => ['2xl', 'h-5 w-5'],
    '3xl' => ['3xl', 'h-6 w-6'],
    '4xl' => ['4xl', 'h-7 w-7'],
    '5xl' => ['5xl', 'h-8 w-8'],
    '6xl' => ['6xl', 'h-9 w-9'],
    '7xl' => ['7xl', 'h-10 w-10'],
]);

it('can prioritize the shorthand over the size attribute')
    ->expect('<x-avatar label="Lorem" size="7xl" sm />')
    ->render()
    ->toContain('w-8 h-8')
    ->not
    ->toContain('w-40 h-40');

it('can render the size from the configuration', function () {
    config()->set('ts-ui.components.avatar.1.size', '3xl');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-avatar label="Lorem" />')->render()->toContain('w-24 h-24');
    } finally {
        config()->set('ts-ui.components.avatar.1.size', 'md');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the size attribute win over the configuration', function () {
    config()->set('ts-ui.components.avatar.1.size', '3xl');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-avatar label="Lorem" size="5xl" />')->render()->toContain('w-32 h-32');
    } finally {
        config()->set('ts-ui.components.avatar.1.size', 'md');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an invalid size', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar label="Lorem" size="9xl" />')->render();
});

it('can render square')
    ->expect('<x-avatar label="Lorem" lg square />')
    ->render()
    ->not
    ->toContain('rounded-full');

it('can render placeholder')
    ->expect('<x-avatar />')
    ->render()
    ->toContain('svg');

it('can render image')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"');

it('can render image with alt')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" text="Beca" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"')
    ->toContain('alt="Beca');

it('can render presence')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('relative inline-flex')
    ->toContain('bg-green-500');

it('cannot let the presence wrapper stretch as a flex item')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('relative inline-flex w-fit');

it('cannot render presence without it')
    ->expect('<x-avatar text="AJ" />')
    ->render()
    ->not
    ->toContain('bg-green-500');

it('can render presence with custom color')
    ->expect('<x-avatar text="AJ" presence presence-color="red" />')
    ->render()
    ->toContain('bg-red-500');

it('can render presence at right-top by default')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('top-0 right-0');

it('can render presence at right-bottom')
    ->expect('<x-avatar text="AJ" presence presence-position="right-bottom" />')
    ->render()
    ->toContain('bottom-0 right-0');

it('can render presence at left-top')
    ->expect('<x-avatar text="AJ" presence presence-position="left-top" />')
    ->render()
    ->toContain('top-0 left-0');

it('can render presence at left-bottom')
    ->expect('<x-avatar text="AJ" presence presence-position="left-bottom" />')
    ->render()
    ->toContain('bottom-0 left-0');

it('can render presence with pulse')
    ->expect('<x-avatar text="AJ" presence pulse />')
    ->render()
    ->toContain('animate-ping');

it('cannot render presence with pulse without pulse')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->not
    ->toContain('animate-ping');

it('cannot use invalid presence position', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar text="AJ" presence presence-position="center" />')->render();
});
