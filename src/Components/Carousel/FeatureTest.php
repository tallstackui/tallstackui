<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('cannot render without images', function () {
    $this->expectException(ViewException::class);

    expect('<x-carousel :images="[]" />')->render();
});

it('renders the lightbox without caption by default', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a', 'title' => 'T', 'description' => 'D']];

    $component = '<x-carousel clickable :images="$images" />';

    expect($component)->render(['images' => $images])
        ->toContain('expanded?.src')
        ->not->toContain('figcaption');
});

it('renders the lightbox with overlay caption', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a', 'title' => 'T', 'description' => 'D']];

    $component = '<x-carousel clickable caption="overlay" :images="$images" />';

    expect($component)->render(['images' => $images])
        ->toContain('<figure')
        ->toContain('<figcaption')
        ->toContain('absolute inset-x-0 bottom-0')
        ->toContain('expanded?.title')
        ->toContain('expanded?.description');
});

it('renders the lightbox with footer caption', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a', 'title' => 'T', 'description' => 'D']];

    $component = '<x-carousel clickable caption="footer" :images="$images" />';

    expect($component)->render(['images' => $images])
        ->toContain('<figure')
        ->toContain('<figcaption')
        ->toContain('flex max-h-full max-w-full flex-col')
        ->toContain('expanded?.title')
        ->toContain('expanded?.description');
});

it('cannot accept invalid caption', function () {
    $this->expectException(ViewException::class);

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel clickable caption="bogus" :images="$images" />')->render(['images' => $images]);
});

it('cannot use caption without clickable', function () {
    $this->expectException(ViewException::class);

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel caption="overlay" :images="$images" />')->render(['images' => $images]);
});

it('renders lightbox navigation buttons and keyboard handlers when navigable is set', function () {
    $images = [
        ['src' => 'a.jpg', 'alt' => 'a'],
        ['src' => 'b.jpg', 'alt' => 'b'],
    ];

    $component = '<x-carousel clickable navigable :images="$images" />';

    expect($component)->render(['images' => $images])
        ->toContain('tallstackui_carousel_expanded_previous')
        ->toContain('tallstackui_carousel_expanded_next')
        ->toContain('expandPrevious()')
        ->toContain('expandNext()')
        ->toContain('keydown.left.window')
        ->toContain('keydown.right.window');
});

it('does not render lightbox navigation buttons when navigable is omitted', function () {
    $images = [
        ['src' => 'a.jpg', 'alt' => 'a'],
        ['src' => 'b.jpg', 'alt' => 'b'],
    ];

    $component = '<x-carousel clickable :images="$images" />';

    expect($component)->render(['images' => $images])
        ->not->toContain('tallstackui_carousel_expanded_previous')
        ->not->toContain('tallstackui_carousel_expanded_next')
        ->not->toContain('expandPrevious()')
        ->not->toContain('expandNext()');
});

it('forwards the slide index to the expand call when navigable is set', function () {
    $images = [
        ['src' => 'a.jpg', 'alt' => 'a'],
        ['src' => 'b.jpg', 'alt' => 'b'],
    ];

    $component = '<x-carousel clickable navigable :images="$images" />';

    expect($component)->render(['images' => $images])
        ->toContain('expand(image, index + 1)');
});

it('cannot use navigable without clickable', function () {
    $this->expectException(ViewException::class);

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel navigable :images="$images" />')->render(['images' => $images]);
});

it('can render round with the default look', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel round :images="$images" />')->render(['images' => $images])
        ->toContain('rounded-xl');
});

it('can render round variations', function (string $round) {
    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel round="'.$round.'" :images="$images" />')->render(['images' => $images])
        ->toContain('rounded-'.$round);
})->with(['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', 'full']);

it('cannot render with an invalid round', function () {
    $this->expectException(ViewException::class);

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel round="foo" :images="$images" />')->render(['images' => $images]);
});
