@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @if ($navigate)
    wire:navigate
@elseif($navigateHover)
    wire:navigate.hover
@endif @endif
{{ $attributes->except('height')->class([
   $customization['wrapper.first'],
   $customization['wrapper.first-clickable'] => $clickable,
   $customization['wrapper.first-chart'] => $charted,
   $customization['shadowless'] => $shadowless,
   $customization['bordered'] => $bordered,
]) }}
x-data="tallstackui_stats(@js($number), @js($animate), @js($duration))"
x-intersect:enter.full="visible = true"
x-intersect:leave="visible = false; start = 0"
x-cloak>
@if ($charted)
    <div class="{{ $customization['chart.wrapper'] }}" aria-hidden="true" dusk="tallstackui_stats_chart">
        @if ($chart instanceof \Illuminate\View\ComponentSlot)
            {{ $chart }}
        @else
            <x-dynamic-component :component="TallStackUi::prefix('chart')"
                                 :series="$chart"
                                 :$color
                                 :height="64"
                                 class="{{ $customization['chart.element'] }}" />
        @endif
    </div>
@endif
@if ($header)
    <div @if ($header instanceof \Illuminate\View\ComponentSlot)
             {{ $header->attributes->class([$customization['slots.header.wrapper']]) }}
         @else
             class="{{ $customization['slots.header.wrapper'] }}"
         @endif>
        <div class="{{ $customization['slots.header.text'] }}">
            {{ $header }}
        </div>
    </div>
@endif
<div @class([
            $customization['wrapper.second'],
            $customization['wrapper.second-no-header'] => ! $header,
            $customization['wrapper.second-no-footer'] => ! $footer,
        ])>
    @if ($icon)
        @if (! $icon instanceof \Illuminate\View\ComponentSlot)
            <div @class([$customization['wrapper.third'], $colors['background']])>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :$icon
                                     internal
                                     class="{{ $customization['icon'] }}" />
            </div>
        @else
            <div {{ $icon->attributes->class([$customization['wrapper.third']]) }}>
                {{ $icon }}
            </div>
        @endif
    @endif
    <div class="grow">
        @if ($title)
            <h2 class="{{ $customization['title'] }}">{{ $title }}</h2>
        @endif
        @if ($slot->isNotEmpty())
            <div x-ref="number">
                {{ $slot }}
            </div>
        @else
            <h2 @class([$customization['number'], $colors['text']]) x-ref="number">{{ $number }}</h2>
        @endif
    </div>
    @if ($right)
        <div @if ($right instanceof \Illuminate\View\ComponentSlot)
                 {{ $right->attributes }}
             @endif>
            {{ $right }}
        </div>
    @elseif ($increase || $decrease)
        <div>
            @if ($increase)
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon($customization['slots.right.increase.icon'])"
                                     internal
                                     class="{{ $customization['slots.right.increase.class'] }}" />
            @else
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon($customization['slots.right.decrease.icon'])"
                                     internal
                                     class="{{ $customization['slots.right.decrease.class'] }}" />
            @endif
        </div>
    @endif
</div>
@if ($footer)
    <div @if ($footer instanceof \Illuminate\View\ComponentSlot)
             {{ $footer->attributes->class([$customization['slots.footer.wrapper']]) }}
         @else
             class="{{ $customization['slots.footer.wrapper'] }}"
         @endif>
        <div class="{{ $customization['slots.footer.text'] }}">
            {{ $footer }}
        </div>
    </div>
@endif
</{{ $tag }}>
