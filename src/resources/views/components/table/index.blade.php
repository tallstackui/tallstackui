@php
    //TODO: key of the action slot should be mandatory
    $interactions = $slots($__env);

    $sortable = fn ($header) => filled($sort) && ($header['sortable'] ?? true);
    $sorted = fn ($header) => $sortable($header) && $sort['column'] === $header['index'];
    $define = function ($header) use ($sort, $sortable) {
        if (! $sortable || blank($sort)) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $sort['direction'] === 'asc' ? 'desc' : 'asc';

        return ['column' => $header['index'], 'direction' => $direction];
    }
@endphp

<div>
    @if (isset($__livewire) && $filters)
        <div @class([
                'mb-4 flex items-center',
                'justify-between' => $quantityPropertyBind && $searchPropertyBind,
                'justify-end' => ! $quantityPropertyBind || ! $searchPropertyBind,
            ])>
            @if ($quantityPropertyBind)
                <div class="w-1/5">
                    <x-select.native label="Quantidade"
                                     :options="$quantities"
                                     wire:model.live.debounce.500ms="{{ $quantityPropertyBind }}" />
                </div>
            @endif
            @if ($searchPropertyBind)
                <div class="sm:w-1/5">
                    <x-input wire:model.live.debounce.500ms="{{ $searchPropertyBind }}"
                             icon="magnifying-glass"
                             placeholder="Procure por algo..."
                             type="search" />
                </div>
            @endif
        </div>
    @endif
    <div class="overflow-auto rounded-lg shadow ring-1 ring-black ring-opacity-5 soft-scrollbar">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-dark-500/50">
            @if (!$withoutHeaders)
                <thead @class([
                    'uppercase',
                    'bg-gray-50 dark:bg-dark-600' => !$striped,
                    'bg-white dark:bg-dark-700' => $striped,
                ])>
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-dark-200 px-3">
                            <a class="group inline-flex cursor-pointer truncate"
                               @if (isset($__livewire) && $sortable($header))
                                   wire:click="$set('sort', {column: '{{ $define($header)['column'] }}', direction: '{{ $define($header)['direction'] }}' })"
                                    @endif>

                                {{ $header['label'] }}

                                @if (isset($__livewire) && $sortable($header) && $sorted($header))
                                    <x-icon :name="$define($header)['direction'] === 'desc' ? 'chevron-up' : 'chevron-down'"  class="w-4 h-4 ml-2" />
                                @endif
                            </a>
                        </th>
                    @endforeach
                </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-gray-200 bg-white dark:bg-dark-700 dark:divide-dark-500/20">
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
                                        if (isset($interactions['action']) && $header['index'] === 'action') {
                                            $key = $slot->attributes->get('key');
                                            $content = $key === null
                                                ? str_replace('$key', json_encode($value), $slot->toHtml())
                                                : str_replace('$key', data_get($value, $key), $slot->toHtml());
                                        } else {
                                            $content = str_replace('$slot', data_get($value, $header['index']), $slot->toHtml());
                                        }
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
        @if ($rows->hasPages())
            <div class="mt-4 flex justify-end">
                {{ $rows->links('tallstack-ui::components.table.paginators.tailwind') }}
            </div>
        @endif

    </div>
</div>
