@php
    use TasteUi\Facades\TasteUi;
    $customize = [];

    $main = TasteUi::personalization('taste-ui::personalizations.alert')->get('main');
    $footer = TasteUi::personalization('taste-ui::personalizations.alert')->get('footer');

    $customize['main'] ??= $tasteUiMainClasses();
    $customize['title'] ??= $tasteUiTitleClasses();
    $customize['text'] ??= $tasteUiTextClasses();
@endphp

<div @class($main ?? $customize['main'])
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($customize['title']['wrapper'])>
            <h3 @class($customize['title']['base'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($customize['title']['icon']['wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" @class($customize['title']['icon']['class']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    @if ($text)
        <div @class($customize['text']['wrapper'])>
            <div @class($customize['text']['title']['wrapper'])>
                <p>{{ $text ?? $slot }}</p>
                {{ $footer }}
            </div>
            @if (!$title && $closeable)
                <div @class($customize['text']['title']['icon']['wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon name="x-mark" @class($customize['text']['title']['icon']['class']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
