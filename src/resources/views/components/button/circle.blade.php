@php
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else role="button" @endif {{ $attributes->except('type')->class([
        $personalize['wrapper.base'],
        $personalize['wrapper.sizes.' . $size],
        $colors['background']
    ]) }} type="{{ $attributes->get('type', $submit ? 'submit' : 'button') }}" @if ($livewire && $loading) wire:loading.attr="disabled" wire:loading.class="!cursor-wait" @endif>
@if ($icon)
    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                         :$icon
                         :attributes="$wireable['icon']"
                         internal
                         @class([$personalize['icon.sizes.' . $size], $colors['icon']]) />
@else
    <span @if ($livewire && $loading) {{ $wireable['text'] }} @endif @class([$personalize['text.sizes.' . $size]])>{{ $text ?? $slot }}</span>
@endif
@if ($livewire && $loading)
    <x-tallstack-ui::icon.generic.loading-button :$loading :$delay @class([
        'animate-spin',
        $personalize['icon.sizes.' . $size],
        $colors['icon']
    ]) />
@endif
</{{ $tag }}>
