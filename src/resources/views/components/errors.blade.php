@php($personalize = $classes())

@if ($errors->count())
    <div @class(['w-full']) x-data="{ show : true }" x-show="show">
        <div {{ $attributes->class([
                $personalize['wrapper'],
                $colors['background']
            ]) }}>
            <div @class([$personalize['title.wrapper'], $colors['border']])>
                <span @class([$personalize['title.text'], $colors['text']])>
                    @if ($icon !== null)
                        <x-dynamic-component :component="$resolver('icon')" :$icon class="w-5 h-5" outline />
                    @endif
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
                @if ($close)
                <button dusk="errors-close-button" class="cursor-pointer" x-on:click="show = false">
                    <x-dynamic-component :component="$resolver('icon')" icon="x-mark" @class([$personalize['close'], $colors['text']]) />
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
        </div>
    </div>
@endif
