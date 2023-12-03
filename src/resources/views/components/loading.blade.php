@php($personalize = tallstackui_personalization('loading', $personalization()))

<div {{ $attributes }} @class([
        $personalize['wrapper.first'],
        $personalize['blur'] => $configurations['blur'] === true,
        $personalize['opacity'] => $configurations['opacity'] === true,
    ])>
    <div @class($personalize['wrapper.second'])>
        @if (!$text && empty($slot->toHtml()))
            <x-tallstack-ui::icon.others.loading @class($personalize['spinner']) />
        @else
            <span @class($personalize['text'])>
                {!! $text ?? $slot !!}
            </span>
        @endif
    </div>
</div>
