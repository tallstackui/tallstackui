@php
    $customize = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.alert')->toArray();

    $customize['main'] ??= $tasteUiMainClasses();

    $customize['title.base'] ??= $tasteUiTitleBaseClasses();
    $customize['title.wrapper'] ??= $tasteUiTitleWrapperClasses();
    $customize['title.icon.wrapper'] ??= $tasteUiTitleIconWrapperClasses();
    $customize['title.icon.classes'] ??= $tasteUiTitleIconBaseClasses();

    $customize['text.wrapper'] ??= $tasteUiTextWrapperClasses();
    $customize['text.title.wrapper'] ??= $tasteUiTextTitleWrapperClasses();
    $customize['text.icon.classes'] ??= $tasteUiTextIconBaseClasses();
    $customize['text.icon.wrapper'] ??= $tasteUiTextIconWrapperClasses();
@endphp

<div @class($customize['main'])
     x-data="{ show : true }"
     x-show="show"
     x-transition.delay.50ms>
    @if ($title)
        <div @class($customize['title.wrapper'])>
            <h3 @class($customize['title.base'])>{{ $title }}</h3>
            @if ($closeable)
                <div @class($customize['title.icon.wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon icon="x-mark" @class($customize['title.icon.classes']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
    @if ($text)
        <div @class($customize['text.wrapper'])>
            <div @class($customize['text.title.wrapper'])>
                <p>{{ $text ?? $slot }}</p>
            </div>
            @if (!$title && $closeable)
                <div @class($customize['text.icon.wrapper'])>
                    <button x-on:click="show = false">
                        <x-icon icon="x-mark" @class($customize['text.icon.classes']) />
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
