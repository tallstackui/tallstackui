@php($personalize = tallstackui_personalization('card', $personalization()))

<div @class($personalize['wrapper.first'])>
    <div @class($personalize['wrapper.second'])>
        @if ($header)
            <div @class($personalize['header.wrapper'])>
                <h3 @class($personalize['header.text'])>
                    {{ $header }}
                </h3>
            </div>
        @endif
        <div {{ $attributes->class($personalize['body']) }}>
            {{ $slot }}
        </div>
        @if ($footer)
            <div @class($personalize['footer.wrapper'])>
                <div @class($personalize['footer.text'])>
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>
