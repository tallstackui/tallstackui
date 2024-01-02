@php
    $tag = $href ? 'a' : 'button';
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else
    role="button"
@endif {{ $attributes->class([
            $personalize['wrapper.base'],
            $personalize['wrapper.sizes.' . $size],
            $colors['background']
        ]) }} wire:loading.attr="disabled" wire:loading.class="!cursor-wait">
@if ($icon)
    @if ($loading)
        @if ($delay === 'longest')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.longest />
        @elseif ($delay === 'longer')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.longer />
        @elseif ($delay === 'long')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.long />
        @elseif ($delay === 'short')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.short />
        @elseif ($delay === 'shorter')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.shorter />
        @elseif ($delay === 'shortest')
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove.delay.shortest />
        @else
            <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) wire:loading.remove />
        @endif
    @else
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) />
    @endif
@else
    <span @if ($loading) wire:loading.remove @endif @class([$personalize['text.sizes.' . $size]])>{{ $text ?? $slot }}</span>
@endif
@if ($loading)
    <x-tallstack-ui::icon.others.loading-button :$loading :$delay @class([
        'animate-spin',
        $personalize['icon.sizes.' . $size],
        $colors['icon']
    ]) />
@endif
</{{ $tag }}>
