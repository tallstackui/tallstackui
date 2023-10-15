@php($customize = tallstackui_personalization('card', $personalization()))

<div @class($customize['wrapper.first'])>
    <div @class($customize['wrapper.second'])>
        @if ($header)
            <div @class($customize['header.wrapper'])>
                <h3 @class($customize['header.text'])>
                    {{ $header }}
                </h3>
            </div>
        @endif
        <div {{ $attributes->class($customize['body']) }}>
            {{ $slot }}
        </div>
        @if ($footer)
            <div @class($customize['footer.wrapper'])>
                <div @class($customize['footer.text'])>
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>
