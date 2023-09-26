@php
    $customize = $customize();

    $customize['main']['base'] ??= $customMainBaseClasses();
    $customize['main']['wrapper'] ??= $customMainWrapperClasses();

    $customize['title']['base'] ??= $customTitleBaseClasses();
    $customize['title']['wrapper'] ??= $customTitleWrapperClasses();

    $customize['body'] ??= $customBodyClasses();
@endphp

@if (($count = $errors->count()) > 0)
    <div @class($customize['main']['base'])>
        <div {{ $attributes->class($customize['main']['wrapper']) }}>
            <div @class($customize['title']['wrapper'])>
                <span @class($customize['title']['base'])>
                    {{ __($title, ['count' => $count]) }}
                </span>
            </div>
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
