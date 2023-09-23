@php($cardElement = $cardElement())

<div @class($cardElement['wrapper']['first'])>
    <div @class($cardElement['wrapper']['second'])>
        <div @class($cardElement['title']['wrapper'])>
            @if ($header)
                <h3 @class($cardElement['title']['text'])>
                    {{ $header }}
                </h3>
            @endif
        </div>
        <div {{ $attributes->class($baseClass()) }}>
            {{ $slot }}
        </div>
        @if ($footer)
            <div @class($cardElement['footer']['wrapper'])>
                <div @class($cardElement['footer']['text'])>
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>
