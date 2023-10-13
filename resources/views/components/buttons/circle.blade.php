@php
    $tag = $href ? 'a' : 'button';
    $customize = tallstackui_personalization('button.circle', $customization());
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else
    role="button"
@endif {{ $attributes->class($customize['base']) }} wire:loading.attr="disabled" wire:loading.class="!cursor-wait">
@if ($icon)
    @if ($loading)
        @if ($delay === 'longest')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.longest />
        @elseif ($delay === 'longer')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.longer />
        @elseif ($delay === 'long')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.long />
        @elseif ($delay === 'short')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.short />
        @elseif ($delay === 'shorter')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.shorter />
        @elseif ($delay === 'shortest')
            <x-icon :$icon @class($customize['icon']) wire:loading.remove.delay.shortest />
        @else
            <x-icon :$icon @class($customize['icon']) wire:loading.remove />
        @endif
    @else
        <x-icon :$icon @class($customize['icon']) />
    @endif
@else
    <span @if ($loading) wire:loading.remove @endif>{{ $text ?? $slot }}</span>
@endif
@if ($loading)
    <svg @if ($loading !== "1") wire:target="{{ $loading }}" @endif
    wire:loading.delay{{ $delay ? ".{$delay}" : "" }}
    @class($customize['icon.loading'])
    dusk="button-loading-spinner"
    xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"></circle>
        <path class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
@endif
</{{ $tag }}>
