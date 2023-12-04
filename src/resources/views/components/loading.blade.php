@php($personalize = tallstackui_personalization('loading', $personalization()))

<div @if (!$delay)
         wire:loading
     @else
         wire:loading.delay{{ is_string($delay) && $delay !== "1" ? ".{$delay}" : "" }}
     @endif @if ($loading) wire:target="{{ $loading }}" @endif {{ $attributes }} @class([
        $personalize['wrapper.first'],
        $personalize['blur'] => $configurations['blur'] === true,
        $personalize['opacity'] => $configurations['opacity'] === true,
    ])>
    <div @class($personalize['wrapper.second'])>
        @if (!$text && empty($slot->toHtml()))
            <x-tallstack-ui::icon.others.loading @class($personalize['spinner']) />
        @else
            <div @class($personalize['text'])>
                {!! $text ?? $slot !!}
            </div>
        @endif
    </div>
</div>
