<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Icon\Component as Icon;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-qr-code link="https://tallstackui.com" />')
    ->render()
    ->toContain('<svg')
    ->toContain('aria-label="https://tallstackui.com"')
    ->toContain('fill="currentColor"');

it('can render with a color')
    ->expect('<x-qr-code link="https://tallstackui.com" color="red" />')
    ->render()
    ->toContain('text-red-600');

it('can render following the theme when no color is given')
    ->expect('<x-qr-code link="https://tallstackui.com" />')
    ->render()
    ->toContain('dark:text-white');

it('can render with a size')
    ->expect('<x-qr-code link="https://tallstackui.com" size="2xl" />')
    ->render()
    ->toContain('size-64');

it('can render with the default size')
    ->expect('<x-qr-code link="https://tallstackui.com" />')
    ->render()
    ->toContain('size-40');

it('can render with an icon watermark')
    ->expect('<x-qr-code link="https://tallstackui.com" watermark="bolt" />')
    ->render()
    ->toContain('tallstackui_qr_code_watermark')
    ->toContain('size-auto');

it('can render an icon watermark whose name is longer than a caption may be', function () {
    // The caption limit exists to fit the strip. An icon is drawn from its
    // view, so the length of its name is none of the component's business.
    expect('<x-qr-code link="https://tallstackui.com" watermark="shopping-cart" />')
        ->render()
        ->toContain('tallstackui_qr_code_watermark')
        ->not->toContain('<text');
});

it('can render with a text watermark')
    ->expect('<x-qr-code link="https://tallstackui.com" watermark="PIX" />')
    ->render()
    ->toContain('<text')
    ->toContain('PIX')
    ->toContain('text-anchor="middle"');

it('can render a caption that looks like an icon name as text when the icons are local', function () {
    $original = config('ts-ui.components.icon');

    // A local set resolves through plain Blade components, and a caption that
    // matches nothing there used to reach the icon component and fail on the
    // missing view instead of falling back.
    config()->set('ts-ui.components.icon', [
        Icon::class,
        ['type' => 'views/components/svg', 'style' => 'solid', 'custom' => ['guide' => []]],
    ]);

    __ts_get_component_configuration('', flush: true);

    try {
        expect('<x-qr-code link="https://tallstackui.com" watermark="foo-bar" />')
            ->render()
            ->toContain('<text')
            ->toContain('foo-bar');
    } finally {
        config()->set('ts-ui.components.icon', $original);

        __ts_get_component_configuration('', flush: true);
    }
});

it('can render a caption that looks like an icon name as text', function () {
    // There is no [foo-bar] icon, and falling through to one would fail on a
    // missing view instead of drawing the caption that was asked for.
    expect('<x-qr-code link="https://tallstackui.com" watermark="foo-bar" />')
        ->render()
        ->toContain('<text')
        ->toContain('foo-bar');
});

it('can render the skeleton')
    ->expect('<x-qr-code skeleton />')
    ->render()
    ->toContain('tallstackui_qr_code_skeleton')
    ->toContain('animate-pulse')
    ->toContain('aria-busy="true"');

it('can render the skeleton without a link')
    ->expect('<x-qr-code skeleton size="xs" />')
    ->render()
    ->toContain('size-24');

it('can render the copy action')
    ->expect('<x-qr-code link="https://tallstackui.com" copy />')
    ->render()
    ->toContain('tallstackui_qr_code_copy')
    ->toContain('tallstackui_qrCode');

it('can render the download action', function () {
    expect('<x-qr-code link="https://tallstackui.com" download />')
        ->render()
        ->toContain('tallstackui_qr_code_download')
        ->toContain('\\u0022format\\u0022:\\u0022png\\u0022');
});

it('can render the download action as a vector', function () {
    expect('<x-qr-code link="https://tallstackui.com" download="svg" />')
        ->render()
        ->toContain('\\u0022format\\u0022:\\u0022svg\\u0022');
});

it('cannot render actions when none was asked for')
    ->expect('<x-qr-code link="https://tallstackui.com" />')
    ->render()
    ->not->toContain('tallstackui_qrCode');

it('cannot render without a link', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code />')->render();
});

it('cannot render with an invalid link', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code link="tallstackui" />')->render();
});

it('cannot render with an invalid size', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code link="https://tallstackui.com" size="9xl" />')->render();
});

it('cannot render with an invalid download format', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code link="https://tallstackui.com" download="webp" />')->render();
});

it('cannot render with an oversized watermark', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code link="https://tallstackui.com" watermark="TallStackUI" />')->render();
});

it('cannot render with a link longer than any version holds', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code link="https://tallstackui.com/?q='.str_repeat('a', 3000).'" />')->render();
});

it('cannot render a negative skeleton', function () {
    $this->expectException(ViewException::class);

    expect('<x-qr-code :skeleton="0" />')->render();
});
