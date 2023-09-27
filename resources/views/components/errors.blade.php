@php
    $personalization = \TasteUi\Facades\TasteUi::personalization('taste-ui::personalizations.alert')->toArray();
    $customize = tasteui_personalize($personalization, $customization());
@endphp

@if (($count = $errors->count()) > 0)
    <div @class($customize['main.base'])>
        <div {{ $attributes->class($customize['main.wrapper']) }}>
            <div @class($customize['title.wrapper'])>
                <span @class($customize['title.base'])>
                    {{ __($title, ['count' => $count]) }}
                </span>
            </div>
            {{-- TODO: body.wrapper --}}
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
