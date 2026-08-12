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
    ]) x-ref="loading" x-data="tallstackui_loading(@js($name), @js($configurations['overflow'] ?? false))">
    <div class="{{ $customization['wrapper.second'] }}">
        @if (!empty($slot->toHtml()))
            <div class="{{ $customization['text'] }}">
                {!! $slot !!}
            </div>
        @elseif ($spinner)
            <x-dynamic-component :component="TallStackUi::prefix('spinner')"
                                 :type="$spinner"
                                 :text="$text" />
        @elseif ($text)
            <div class="{{ $customization['text'] }}">
                {!! $text !!}
            </div>
        @else
            <x-ts-ui::icon.generic.loading class="{{ $customization['spinner'] }}" />
        @endif
    </div>
</div>
