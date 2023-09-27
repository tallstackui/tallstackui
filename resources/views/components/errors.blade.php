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
            <div class="mt-2 ml-5 pl-1">
                <ul @class($customize['body'])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
