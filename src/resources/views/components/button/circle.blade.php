@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{!! $href !!}" @else
    role="button"
@endif {{ $attributes->except('type')->class([
        $customization['wrapper.base'],
        $customization['wrapper.sizes.' . $size],
        $colors['background']
    ]) }} type="{{ $attributes->get('type', $submit ? 'submit' : 'button') }}" @if ($livewire && $loading)
    wire:loading.attr="disabled" wire:loading.class="!cursor-wait"
@endif>
@if ($icon)
    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                         :$icon
                         :attributes="$wireable['icon']"
                         internal
            @class([$customization['icon.sizes.' . $size], $colors['icon']]) />
@else
    <span @if ($livewire && $loading)
        {{ $wireable['text'] }}
            @endif @class([$customization['text.sizes.' . $size]])>{{ $text ?? $slot }}</span>
@endif
@if ($livewire && $loading)
    <x-ts-ui::icon.generic.loading-button :$loading :$delay @class([
        'animate-spin',
        $customization['icon.sizes.' . $size],
        $colors['icon']
    ]) />
@endif
</{{ $tag }}>
