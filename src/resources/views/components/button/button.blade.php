@php
    $customization = $classes();
@endphp

<{{ $tag }} @if ($href) href="{!! $href !!}" @else
    role="button"
@endif {{ $attributes->except('type')->class([
        $customization['wrapper.class'],
        $customization['wrapper.sizes.' . $size],
        $colors['background'],
        'w-full' => $block,
        $customization['wrapper.border.radius.rounded'] => !$square && !$round,
        $customization['wrapper.border.radius.circle'] => !$square && $round !== null,
    ]) }} type="{{ $attributes->get('type', $submit ? 'submit' : 'button') }}" @if ($livewire && $loading)
    wire:loading.attr="disabled" wire:loading.class="!cursor-wait"
@endif @if ($tooltip)
    x-tooltip="{{ $tooltip }}"
@endif>
@if ($livewire && $loading && $position === 'left')
    <x-ts-ui::icon.generic.loading-button :$loading :$delay @class([
                'animate-spin',
                $customization['icon.sizes.' . $size],
                $colors['icon'],
            ]) />
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
    <x-ts-ui::icon.generic.loading-button :$loading :$delay @class([
            'animate-spin',
            $customization['icon.sizes.' . $size],
            $colors['icon'],
        ]) />
@endif
</{{ $tag }}>
