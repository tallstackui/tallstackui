@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.card')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

<div @class($customize['wrapper.first'])>
    <div @class($customize['wrapper.second'])>
        <div @class($customize['title.wrapper'])>
            @if ($header)
                <h3 @class($customize['title.text'])>
                    {{ $header }}
                </h3>
            @endif
        </div>
        <div {{ $attributes->class($customize['main']) }}>
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
