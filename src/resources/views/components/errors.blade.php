@php($customize = tallstackui_personalization('errors', $personalization()))

@if ($errors->count())
    <div @class([$customize['wrapper.first'], 'animate-pulse' => $pulse])>
        <div {{ $attributes->class([
                $customize['wrapper.second'],
                $colors['wrapper.second.color']
            ]) }}>
            <div @class([$customize['title.wrapper'], $colors['title.wrapper.color']])>
                <span @class([$customize['title.text'], $colors['title.text.color']])>
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
            </div>
            <div @class($customize['body.wrapper'])>
                <ul @class([$customize['body.list'], $colors['body.list.color']])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
