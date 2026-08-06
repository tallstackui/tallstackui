<div @class([
    $customization['tile.base'],
    $customization['tile.performance'],
    $customization['tile.rounded'] => $round,
    $customization['masonry.tile'] => $layout === 'masonry',
    ($ratioClass ?? '') => filled($ratioClass),
])>
    @if ($clickable)
        <button type="button"
                class="{{ $customization['tile.trigger'] }}"
                dusk="tallstackui_gallery_expand_{{ $index }}"
                x-on:click="expand(images[{{ $index }}], {{ $index + 1 }})">
            <img src="{{ $image['src'] ?? '' }}"
                 alt="{{ $image['alt'] ?? '' }}"
                 loading="lazy"
                 decoding="async"
                 @isset($image['width']) width="{{ $image['width'] }}" @endisset
                 @isset($image['height']) height="{{ $image['height'] }}" @endisset
                 @class([$customization['tile.image'], $customization['tile.rounded'] => $round]) />
            @if ($overlay > 0)
                <div class="{{ $customization['feature.remaining.wrapper'] }}">
                    <span class="{{ $customization['feature.remaining.text'] }}">+{{ $overlay }}</span>
                </div>
            @endif
        </button>
    @else
        <a @isset($image['url']) href="{{ $image['url'] }}" @endisset
           @isset($image['target']) target="{{ $image['target'] }}" @endisset
           class="{{ $customization['tile.link'] }}">
            <img src="{{ $image['src'] ?? '' }}"
                 alt="{{ $image['alt'] ?? '' }}"
                 loading="lazy"
                 decoding="async"
                 @isset($image['width']) width="{{ $image['width'] }}" @endisset
                 @isset($image['height']) height="{{ $image['height'] }}" @endisset
                 @class([$customization['tile.image'], $customization['tile.rounded'] => $round]) />
            @if ($overlay > 0)
                <div class="{{ $customization['feature.remaining.wrapper'] }}">
                    <span class="{{ $customization['feature.remaining.text'] }}">+{{ $overlay }}</span>
                </div>
            @endif
        </a>
    @endif
</div>
