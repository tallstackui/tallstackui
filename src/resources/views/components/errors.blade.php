@php($personalize = tallstackui_personalization('errors', $personalization()))

@if ($errors->count())
    <div @class(['w-full', 'animate-pulse' => $pulse])>
        <div {{ $attributes->class([
                $personalize['wrapper'],
                $colors['wrapper.second.color']
            ]) }}>
            <div @class([$personalize['title.wrapper'], $colors['title.wrapper.color']])>
                <span @class([$personalize['title.text'], $colors['title.text.color']])>
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
            </div>
            <div @class($personalize['body.wrapper'])>
                <ul @class([$personalize['body.list'], $colors['body.list.color']])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
