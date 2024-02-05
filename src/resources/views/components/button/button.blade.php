@php
    $tag = $href ? 'a' : 'button';
    $personalize = $classes();
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else role="button" @endif {{ $attributes->class([
        $personalize['wrapper.class'],
        $personalize['wrapper.sizes.' . $size],
        $colors['background'],
        'rounded-md' => !$square && !$round,
        'rounded-full' => !$square && $round !== null,
    ]) }} @if ($livewire) wire:loading.attr="disabled" wire:loading.class="!cursor-wait" @endif>
    @if ($left)
        {!! $left !!}
    @elseif ($icon && $position === 'left')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) />
    @endif
    {{ $text ?? $slot }}
    @if ($right)
        {!! $right !!}
    @elseif ($icon && $position === 'right')
        <x-dynamic-component :component="TallStackUi::component('icon')" :$icon @class([$personalize['icon.sizes.' . $size], $colors['icon']]) />
    @endif
    @if ($livewire && $loading)
        <x-tallstack-ui::icon.generic.loading-button :$loading :$delay @class([
            'animate-spin',
            $personalize['icon.sizes.' . $size],
            $colors['icon'],
        ]) />
    @endif
</{{ $tag }}>
