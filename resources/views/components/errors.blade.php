@if (($count = $errors->count()) > 0)
    <div class="mx-auto max-w-lg">
        <div {{ $attributes->class([
                'rounded-lg p-4',
                'bg-red-50'    => $color === 'red',
                'bg-yellow-50' => $color === 'yellow',
            ]) }}>
            <div @class([
                    'flex items-center border-b-2 pb-3',
                    'text-red-800 border-red-200'       => $color === 'red',
                    'text-yellow-800 border-yellow-200' => $color === 'yellow',
                ])>
                <span @class([
                        'text-sm font-semibold',
                        'text-red-800'    => $color === 'red',
                        'text-yellow-800' => $color === 'yellow'
                    ])>
                    {{ str_replace('$__count', $count, $title) }}
                </span>
            </div>
            <div class="mt-2 ml-5 pl-1">
                <ul @class([
                        'list-disc text-sm space-y-1 text-negative-700',
                        'text-red-800'    => $color === 'red',
                        'text-yellow-800' => $color === 'yellow'
                    ])>
                    @foreach ($messages($errors) as $message)
                        <li>{{ head($message) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
