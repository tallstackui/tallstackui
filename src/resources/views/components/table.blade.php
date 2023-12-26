@php
    $interactions = $slots($__env);
@endphp

<div class="overflow-auto rounded-lg shadow ring-1 ring-black ring-opacity-5 custom-scrollbar">
    <table class="min-w-full divide-y divide-gray-300 dark:divide-dark-500/50">
        @if (!$withoutHeaders)
            <thead class="bg-gray-50 uppercase dark:bg-dark-600">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-dark-200 px-3">
                            <a class="group inline-flex cursor-pointer truncate">
                                {{ $header['label'] }}
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-200 bg-white dark:bg-dark-700 dark:dark:divide-dark-500/50">
            @forelse ($rows as $key => $value)
                <tr @class([ 'bg-gray-50 dark:bg-dark-600' => $striped && $loop->index % 2 === 0])>
                    @foreach ($headers as $header)
                        <td class="whitespace-nowrap py-4 text-sm px-3 text-gray-500 dark:text-dark-300">
                            @if (isset($interactions[$header['index']]))
                                @php
                                    /** @var \Illuminate\View\ComponentSlot $slot */
                                    $slot = $interactions[$header['index']];

                                    if ($slot->attributes->has('override')) {
                                        $override = $slot->attributes->get('override');
                                        $override = str_replace('row.', '', $override);

                                        if ($override !== '' && ((string) $override === (string) $key)) {
                                            $content = $slot->toHtml();
                                        } else {
                                            $content = data_get($value, $header['index']);
                                        }
                                    } else {
                                        $content = str_replace('$slot', data_get($value, $header['index']), $slot->toHtml());
                                    }
                                @endphp
                                {!! $content !!}
                            @else
                                {{ data_get($value, $header['index']) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="whitespace-nowrap py-4 text-sm px-3 text-gray-500 dark:text-dark-300">
                        No data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
