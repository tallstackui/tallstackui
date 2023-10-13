@php
    $customize = tallstackui_personalization('errors', $customization());
    $internal  = $internals();
@endphp

@if ($errors->count())
    <div @class($customize['base.wrapper.first'])>
        <div {{ $attributes->class([
                $customize['base.wrapper.second'],
                $internal['base.wrapper.second.color']
            ]) }}>
            <div @class([$customize['title.wrapper'], $internal['title.wrapper.color']])>
                <span @class([$customize['title.base'], $internal['title.base.color']])>
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
