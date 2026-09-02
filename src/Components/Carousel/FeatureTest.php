<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Carousel\Component;
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

it('does not render the thumbnail strip by default', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel :images="$images" />')->render(['images' => $images])
        ->not->toContain('tallstackui_carousel_thumbnails')
        ->not->toContain('tallstackui_carousel_thumbnail"');
});

it('renders the thumbnail strip with thumbnails', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_carousel_thumbnails')
        ->toContain('tallstackui_carousel_thumbnail"')
        ->toContain('x-for="(image, index) in tiles"')
        ->toContain('seek(index + 1)')
        ->toContain('aria-current')
        ->not->toContain('tallstackui_carousel_thumbnail_more');
});

it('renders the remaining overlay when the images exceed the limit', function () {
    $images = collect(range(1, 9))->map(fn (int $index) => ['src' => "{$index}.jpg", 'alt' => "image-{$index}"])->toArray();

    expect('<x-carousel thumbnails :limit="4" :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_carousel_thumbnail_more')
        ->toContain('index + 1 === 4')
        ->toContain('+5</span>');
});

it('passes the limit to the alpine component', function () {
    $images = collect(range(1, 9))->map(fn (int $index) => ['src' => "{$index}.jpg", 'alt' => "image-{$index}"])->toArray();

    expect('<x-carousel thumbnails :limit="4" :images="$images" />')->render(['images' => $images])
        ->toContain(', 4)"');
});

it('does not render the remaining overlay when the images fit the limit', function () {
    $images = collect(range(1, 6))->map(fn (int $index) => ['src' => "{$index}.jpg", 'alt' => "image-{$index}"])->toArray();

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_carousel_thumbnails')
        ->not->toContain('tallstackui_carousel_thumbnail_more');
});

it('applies round to the thumbnail tiles', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel thumbnails round="full" :images="$images" />')->render(['images' => $images])
        ->toContain('sm:w-20 rounded-full');
});

it('can render the thumbnails through the global configuration', function () {
    config()->set('ts-ui.components.carousel.1.thumbnails', true);

    __ts_get_component_configuration(Component::class, flush: true);

    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_carousel_thumbnails');

    config()->set('ts-ui.components.carousel.1.thumbnails', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global thumbnails through the inline prop', function () {
    config()->set('ts-ui.components.carousel.1.thumbnails', true);

    __ts_get_component_configuration(Component::class, flush: true);

    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel :thumbnails="false" :images="$images" />')->render(['images' => $images])
        ->not->toContain('tallstackui_carousel_thumbnails');

    config()->set('ts-ui.components.carousel.1.thumbnails', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render the limit through the global configuration', function () {
    config()->set('ts-ui.components.carousel.1.limit', 3);

    __ts_get_component_configuration(Component::class, flush: true);

    $images = collect(range(1, 5))->map(fn (int $index) => ['src' => "{$index}.jpg", 'alt' => "image-{$index}"])->toArray();

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->toContain('index + 1 === 3')
        ->toContain('+2</span>');

    config()->set('ts-ui.components.carousel.1.limit', 6);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('cannot use limit without thumbnails', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [limit] can only be used along with [thumbnails].');

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel :limit="4" :images="$images" />')->render(['images' => $images]);
});

it('cannot use a limit lower than 2', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [limit] must be at least 2.');

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel thumbnails :limit="1" :images="$images" />')->render(['images' => $images]);
});

it('hides the indicators when thumbnails is set', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel :images="$images" />')->render(['images' => $images])
        ->toContain('seek(index + 1)')
        ->toContain('rounded-full transition');

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->not->toContain('rounded-full transition');
});

it('can render the thumbnails without the highlight', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->toContain('ring-primary-500');

    expect('<x-carousel thumbnails without-highlight :images="$images" />')->render(['images' => $images])
        ->not->toContain('ring-primary-500')
        ->toContain('ring-gray-200')
        ->toContain('aria-current');
});

it('cannot use without-highlight without thumbnails', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [without-highlight] can only be used along with [thumbnails].');

    $images = [['src' => 'a.jpg', 'alt' => 'a']];

    expect('<x-carousel without-highlight :images="$images" />')->render(['images' => $images]);
});

it('can render the thumbnails without the highlight through the global configuration', function () {
    config()->set('ts-ui.components.carousel.1.without-highlight', true);

    __ts_get_component_configuration(Component::class, flush: true);

    $images = [['src' => 'a.jpg', 'alt' => 'a'], ['src' => 'b.jpg', 'alt' => 'b']];

    expect('<x-carousel thumbnails :images="$images" />')->render(['images' => $images])
        ->not->toContain('ring-primary-500')
        ->toContain('ring-gray-200');

    expect('<x-carousel thumbnails :without-highlight="false" :images="$images" />')->render(['images' => $images])
        ->toContain('ring-primary-500');

    expect('<x-carousel :images="$images" />')->render(['images' => $images])
        ->not->toContain('tallstackui_carousel_thumbnails');

    config()->set('ts-ui.components.carousel.1.without-highlight', false);

    __ts_get_component_configuration(Component::class, flush: true);
});
