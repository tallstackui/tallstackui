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
