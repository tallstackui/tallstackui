@php($customize = tasteui_personalization('errors', $customization()))

@if ($errors->count())
    <div @class($customize['base.wrapper.first'])>
        <div {{ $attributes->class($customize['base.wrapper.second']) }}>
            <div @class($customize['title.wrapper'])>
                <span @class($customize['title.base'])>
                    {{ __($title, ['count' => $count($errors)]) }}
                </span>
            </div>
            <div @class($customize['body.wrapper'])>
                <ul @class($customize['body.list'])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
