@php
    $customization = $classes();
@endphp

<div @if (!$delay)
         wire:loading
     @else
         wire:loading.delay{{ is_string($delay) && $delay !== "1" ? ".{$delay}" : "" }}
     @endif @if ($loading) wire:target="{{ $loading }}" @endif {{ $attributes }} @class([
        $configurations['zIndex'],
        $customization['wrapper.first'],
        $customization['blur'] => $configurations['blur'] === true,
        $customization['opacity'] => $configurations['opacity'] === true,
    ]) x-ref="loading" x-data="tallstackui_loading(@js($this->getName()), @js($configurations['overflow'] ?? false))">
    <div class="{{ $customization['wrapper.second'] }}">
        @if (!$text && empty($slot->toHtml()))
            <x-ts-ui::icon.generic.loading class="{{ $customization['spinner'] }}" />
        @else
            <div class="{{ $customization['text'] }}">
                {!! $text ?? $slot !!}
            </div>
        @endif
    </div>
</div>
