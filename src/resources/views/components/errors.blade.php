@php
    $personalize = $classes();
@endphp

@if ($errors->count())
    <div @class(['w-full'])
         x-data="{ show : true, close () { this.show = false; this.$el.dispatchEvent(new CustomEvent('close')) } }"
         x-show="show">
        <div {{ $attributes->class([
                $personalize['wrapper'],
                $colors['background']
            ]) }}>
            <div @class([$personalize['title.wrapper'], $colors['border']])>
                <span @class([$personalize['title.text'], $colors['text']])>
                    @if ($icon !== null)
                        <x-dynamic-component :component="TallStackUi::component('icon')"
                                             :icon="TallStackUi::icon($icon)"
                                             class="w-5 h-5" outline />
                    @endif
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
                @if ($close)
                <button dusk="errors-close-button"
                        class="cursor-pointer"
                        {{ $attributes->only('x-on:close') }}
                        x-on:click="close()">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         @class([$personalize['close'], $colors['text']]) />
                </button>
                @endif
            </div>
            <div @class($personalize['body.wrapper'])>
                <ul @class([$personalize['body.list'], $colors['text']])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
            @if (is_string($footer))
                <p @class($personalize['slots.footer'])>{{ $footer }}</p>
            @else
                {{ $footer }}
            @endif
        </div>
    </div>
@endif
