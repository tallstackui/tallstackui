@php($customize = tallstackui_personalization('card', $customization()))

<div @class($customize['wrapper.first'])>
    <div @class($customize['wrapper.second'])>
        @if ($header)
            <div @class($customize['title.wrapper'])>
                <h3 @class($customize['title.text'])>
                    {{ $header }}
                </h3>
            </div>
        @endif
        <div {{ $attributes->class($customize['base']) }}>
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
