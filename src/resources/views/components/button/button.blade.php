@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{!! $href !!}" @else
    role="button"
        @endif @if ($unfocus) data-tsui-unfocus @endif {{ $attributes->except('type')->class([
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $colors['background'],
        $customization['wrapper.block'] => $block,
        $customization['border.radius.' . $rounded] => !$square,
    ]) }} type="{{ $attributes->get('type', $submit ? 'submit' : 'button') }}" @if ($livewire && $loading)
    wire:loading.attr="disabled" wire:loading.class="{{ $customization['wire.loading-cursor'] }}"
@endif @if ($tooltip)
    x-tooltip="{{ $tooltip }}"
@endif>
@if ($livewire && $loading && $position === 'left')
    <x-ts-ui::icon.generic.loading-button :$loading :$delay :$spinner :$size :customization="$customization" @class([$colors['icon']]) />
@endif
@if ($left)
    {!! $left !!}
@elseif ($icon && $position === 'left')
    <x-dynamic-component :component="TallStackUi::prefix('icon')" internal
                         :$icon @class([$customization['icon.sizes.' . $size], $colors['icon']]) />
@endif
{{ $text ?? $slot }}
@if ($right)
    {!! $right !!}
@elseif ($icon && $position === 'right')
    <x-dynamic-component :component="TallStackUi::prefix('icon')" internal
                         :$icon @class([$customization['icon.sizes.' . $size], $colors['icon']]) />
@endif
@if ($livewire && $loading && $position === 'right')
    <x-ts-ui::icon.generic.loading-button :$loading :$delay :$spinner :$size :customization="$customization" @class([$colors['icon']]) />
@endif
</{{ $tag }}>
