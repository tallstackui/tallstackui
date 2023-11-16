@php($personalize = tallstackui_personalization('errors', $personalization()))

@if ($errors->count())
    <div @class(['w-full'])>
        <div {{ $attributes->class([
                $personalize['wrapper'],
                $colors['background']
            ]) }}>
            <div @class([$personalize['title.wrapper'], $colors['border']])>
                <span @class([$personalize['title.text'], $colors['text']])>
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
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
