@php
    $tag = $href ? 'a' : 'div';
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif 
     {{ $attributes->class($personalize['wrapper.first']) }}
     x-data="tallstackui_stats(@js($number), @js($animated), @js($duration))"
     x-intersect:enter.full="visible = true"
     x-intersect:leave="visible = false; start = 0"
     x-cloak>
    @if ($header) {{ $header }} @endif
    <div @class([
            'mx-4' => !$slot->isNotEmpty(),
            'mt-4' => !$header, 
            'mb-4' => !$footer, 
            $personalize['wrapper.second'],
        ])>
        @if ($icon)
            <div @class([$personalize['wrapper.third'], $colors['background']])>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :$icon
                                     @class($personalize['icon']) />
            </div>
        @endif
        <div class="flex-grow">
            <h2 @class($personalize['title'])>{{ $title }}</h2>
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <h2 @class($personalize['number']) x-ref="number">{{ $number }}</h2>
            @endif
        </div>
        @if ($side) {{ $side }} @endif
    </div>
    @if ($footer) {{ $footer }} @endif
</{{ $tag }}>
