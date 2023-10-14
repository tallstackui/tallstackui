@php
    $customize = tallstackui_personalization('errors', $personalization());
    $internal = $internals();
@endphp

@if ($errors->count())
    <div @class([$customize['wrapper.first'], 'animate-pulse' => $pulse])>
        <div {{ $attributes->class([
                $customize['wrapper.second'],
                $internal['wrapper.second.color']
            ]) }}>
            <div @class([$customize['title.wrapper'], $internal['title.wrapper.color']])>
                <span @class([$customize['title.text'], $internal['title.text.color']])>
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
            </div>
            <div @class($customize['body.wrapper'])>
                <ul @class([$customize['body.list'], $internal['body.list.color']])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
