@if (($count = $errors->count()) > 0)
    <div @class($baseClass())>
        <div {{ $attributes->class($wrapperClass()) }}>
            <div @class($titleWrapperClass())>
                <span @class($titleClass())>
                    {{ __($title, ['count' => $count]) }}
                </span>
            </div>
            <div class="mt-2 ml-5 pl-1">
                <ul @class($bodyClass())>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
