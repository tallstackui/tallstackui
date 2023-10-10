@php
    $tag = $href ? 'a' : 'button';
    $customize = tallstackui_personalization('button', $customization());
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else
    type="button" role="button"
@endif {{ $attributes->class($customize['wrapper']) }} @if ($loading) wire:loading.attr="disabled" wire:loading.class="!cursor-wait" @endif>
@if ($icon && $position === 'left')
    <x-icon :$icon @class($customize['icon']) />
@endif
{{ $text ?? $slot }}
@if ($icon && $position === 'right')
    <x-icon :$icon @class($customize['icon']) />
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
