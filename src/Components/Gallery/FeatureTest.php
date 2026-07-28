<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

function gallery_images(int $quantity = 3): array
{
    return collect(range(1, $quantity))
        ->map(fn (int $number): array => [
            'src' => "image-{$number}.jpg",
            'alt' => "Alt {$number}",
            'title' => "Title {$number}",
            'description' => "Description {$number}",
        ])
        ->toArray();
}

it('cannot render without images', function () {
    $this->expectException(ViewException::class);

    expect('<x-gallery :images="[]" />')->render();
});

it('falls back to the grid layout when no flag is given', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->toContain('grid-cols-2 sm:grid-cols-3')
        ->not->toContain('columns-2 sm:columns-3');
});

it('renders a single layout flag without tripping the exclusivity rule', function () {
    $images = gallery_images();

    expect('<x-gallery masonry :images="$images" />')->render(['images' => $images])
        ->toContain('columns-2 sm:columns-3');
});

it('cannot use layout flags together', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery grid masonry :images="$images" />')->render(['images' => $images]);
});

it('resolves the column classes from the static map', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" :columns="6" />')->render(['images' => $images])
        ->toContain('grid-cols-2 sm:grid-cols-4 lg:grid-cols-6');

    expect('<x-gallery masonry :images="$images" :columns="4" />')->render(['images' => $images])
        ->toContain('columns-2 sm:columns-3 lg:columns-4');
});

it('cannot use columns out of the supported range', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery :images="$images" :columns="7" />')->render(['images' => $images]);
});

it('resolves the ratio class', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->toContain('aspect-square');

    expect('<x-gallery :images="$images" ratio="portrait" />')->render(['images' => $images])
        ->toContain('aspect-[3/4]');
});

it('cannot use an unsupported ratio', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery :images="$images" ratio="circle" />')->render(['images' => $images]);
});

it('cannot use ratio with masonry', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery masonry ratio="square" :images="$images" />')->render(['images' => $images]);
});

it('cannot use columns with feature', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery feature :columns="4" :images="$images" />')->render(['images' => $images]);
});

it('cannot use limit outside of feature', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery :limit="5" :images="$images" />')->render(['images' => $images]);
});

it('cannot use a limit below two', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery feature :limit="1" :images="$images" />')->render(['images' => $images]);
});

it('renders lazy loading attributes on every tile', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->toContain('loading="lazy"')
        ->toContain('decoding="async"')
        ->toContain('[content-visibility:auto]');
});

it('renders the dimension attributes when they are supplied', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a', 'width' => 800, 'height' => 600]];

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->toContain('width="800"')
        ->toContain('height="600"');
});

it('renders a link when the gallery is not clickable', function () {
    $images = [['src' => 'a.jpg', 'alt' => 'a', 'url' => 'https://tallstackui.com', 'target' => '_blank']];

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->toContain('href="https://tallstackui.com"')
        ->toContain('target="_blank"')
        ->not->toContain('tallstackui_gallery_expand_0');
});

it('renders the expand trigger when the gallery is clickable', function () {
    $images = gallery_images();

    expect('<x-gallery clickable :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_gallery_expand_0')
        ->toContain('tallstackui_gallery_close')
        ->toContain('expand(images[1], 2)');
});

it('renders the feature layout with the first image as the cover', function () {
    $images = gallery_images(7);

    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->toContain('aspect-video')
        ->toContain('image-1.jpg')
        ->toContain('image-7.jpg');
});

it('honours the cover flag in the feature layout', function () {
    $images = gallery_images(3);
    $images[1]['cover'] = true;

    // The cover leaves the thumbnail row, so the row starts at the first image.
    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->toContain('aspect-video');
});

it('renders the remaining overlay when the array exceeds the limit', function () {
    $images = gallery_images(20);

    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->toContain('+13');
});

it('does not render the remaining overlay when the array fits the limit', function () {
    $images = gallery_images(7);

    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->not->toContain('bg-black/60');
});

it('renders only the cover when the feature layout receives a single image', function () {
    $images = gallery_images(1);

    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->toContain('image-1.jpg')
        ->not->toContain('grid-cols-3');
});

it('renders the lightbox without caption by default', function () {
    $images = gallery_images();

    expect('<x-gallery clickable :images="$images" />')->render(['images' => $images])
        ->toContain('expanded?.src')
        ->not->toContain('figcaption');
});

it('renders the lightbox with overlay caption', function () {
    $images = gallery_images();

    expect('<x-gallery clickable caption="overlay" :images="$images" />')->render(['images' => $images])
        ->toContain('<figure')
        ->toContain('<figcaption')
        ->toContain('absolute inset-x-0 bottom-0')
        ->toContain('expanded?.title')
        ->toContain('expanded?.description');
});

it('renders the lightbox with footer caption', function () {
    $images = gallery_images();

    expect('<x-gallery clickable caption="footer" :images="$images" />')->render(['images' => $images])
        ->toContain('<figure')
        ->toContain('<figcaption')
        ->toContain('flex max-h-full max-w-full flex-col');
});

it('cannot use an unsupported caption', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery clickable caption="side" :images="$images" />')->render(['images' => $images]);
});

it('cannot use caption without clickable', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery caption="overlay" :images="$images" />')->render(['images' => $images]);
});

it('cannot use navigable without clickable', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery navigable :images="$images" />')->render(['images' => $images]);
});

it('cannot use without-loop without clickable', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery without-loop :images="$images" />')->render(['images' => $images]);
});

it('renders the navigation buttons only when navigable', function () {
    $images = gallery_images();

    expect('<x-gallery clickable navigable :images="$images" />')->render(['images' => $images])
        ->toContain('tallstackui_gallery_expanded_previous')
        ->toContain('tallstackui_gallery_expanded_next');

    expect('<x-gallery clickable :images="$images" />')->render(['images' => $images])
        ->not->toContain('tallstackui_gallery_expanded_next');
});

it('merges the attributes from the consumer into the root element', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" class="max-w-md" id="foo" style="opacity:1" />')->render(['images' => $images])
        ->toContain('max-w-md')
        ->toContain('relative w-full')
        ->toContain('id="foo"')
        ->toContain('opacity:1');
});

it('renders a scrollable wrapper when a height is given', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" height="60" />')->render(['images' => $images])
        ->toContain('custom-scrollbar')
        ->toContain('overflow-y-auto')
        ->toContain('max-h-60');
});

it('does not render the scrollable wrapper without a height', function () {
    $images = gallery_images();

    expect('<x-gallery :images="$images" />')->render(['images' => $images])
        ->not->toContain('overflow-y-auto')
        ->not->toContain('max-h-60');
});

it('cannot use an unsupported height', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery :images="$images" height="24rem" />')->render(['images' => $images]);
});

it('locks the side thumbnail column to the cover height and scrolls inside it', function () {
    $images = gallery_images(9);

    expect('<x-gallery feature thumbnails="left" :images="$images" />')->render(['images' => $images])
        ->toContain('custom-scrollbar')
        ->toContain('sm:absolute')
        ->toContain('sm:inset-y-0')
        ->toContain('sm:overflow-y-auto');
});

it('places the feature thumbnails below the cover by default', function () {
    $images = gallery_images(7);

    expect('<x-gallery feature :images="$images" />')->render(['images' => $images])
        ->toContain('flex flex-col gap-2')
        ->toContain('grid grid-cols-3 gap-2 sm:grid-cols-6');
});

it('places the feature thumbnails beside the cover', function () {
    $images = gallery_images(7);

    // The cover drops to sm:w-auto so the margin actually reserves the column's
    // space: a margin cannot shrink an element that is already 100% wide.
    expect('<x-gallery feature thumbnails="left" :images="$images" />')->render(['images' => $images])
        ->toContain('sm:left-0')
        ->toContain('sm:ml-26')
        ->toContain('sm:w-auto')
        ->toContain('sm:w-24');

    expect('<x-gallery feature thumbnails="right" :images="$images" />')->render(['images' => $images])
        ->toContain('sm:right-0')
        ->toContain('sm:mr-26')
        ->toContain('sm:w-auto')
        ->toContain('sm:w-24');
});

it('renders every thumbnail beside the cover', function () {
    $images = gallery_images(7);

    // Regression: the side column must render all limit-1 thumbnails, not
    // collapse them away, so every tile trigger has to be present.
    $rendered = expect('<x-gallery feature thumbnails="left" clickable :limit="7" :images="$images" />')
        ->render(['images' => $images]);

    foreach (range(1, 6) as $index) {
        $rendered->toContain('tallstackui_gallery_expand_'.$index);
    }
});

it('cannot use an unsupported thumbnails position', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images(7);

    expect('<x-gallery feature thumbnails="top" :images="$images" />')->render(['images' => $images]);
});

it('cannot use thumbnails outside of feature', function () {
    $this->expectException(ViewException::class);

    $images = gallery_images();

    expect('<x-gallery thumbnails="left" :images="$images" />')->render(['images' => $images]);
});

it('renders the header and footer slots', function () {
    $images = gallery_images();

    $component = <<<'HTML'
    <x-gallery :images="$images">
        <x-slot:header>Header content</x-slot:header>
        <x-slot:footer>Footer content</x-slot:footer>
    </x-gallery>
    HTML;

    expect($component)->render(['images' => $images])
        ->toContain('Header content')
        ->toContain('Footer content');
});
