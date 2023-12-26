@php
    $livewire = isset($__livewire);

    //TODO: key of the action slot should be mandatory
    //$interactions = $slots($__env);

    $sortable = fn ($header) => filled($sort) && ($header['sortable'] ?? true);
    $sorted = fn ($header) => $sortable($header) && $sort['column'] === $header['index'];
    $define = function ($header) use ($sort, $sortable) {
        if (! $sortable || blank($sort)) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $sort['direction'] === 'asc' ? 'desc' : 'asc';

        return ['column' => $header['index'], 'direction' => $direction];
    };

    $target = [];

    if ($quantityPropertyBind) {
        $target[] = $quantityPropertyBind;
    }

    if ($searchPropertyBind) {
        $target[] = $searchPropertyBind;
    }

    $target = implode(',', $target);
@endphp

<div>
    @if ($livewire && $filters)
        <div @class([
                'mb-4 flex items-end',
                'justify-between' => $quantityPropertyBind && $searchPropertyBind,
                'justify-end' => ! $quantityPropertyBind || ! $searchPropertyBind,
            ])>
            @if ($quantityPropertyBind)
                <div class="w-1/5">
                    <x-select.styled :label="$placeholders['quantity']"
                                     :options="$quantities"
                                     wire:model.live="{{ $quantityPropertyBind }}" />
                </div>
            @endif
            @if ($searchPropertyBind)
                <div class="sm:w-1/5">
                    <x-input wire:model.live.debounce.500ms="{{ $searchPropertyBind }}"
                             icon="magnifying-glass"
                             :placeholder="$placeholders['search']"
                             type="search" />
                </div>
            @endif
        </div>
    @endif
    <div class="overflow-auto rounded-lg shadow ring-1 ring-gray-300 dark:ring-dark-600 soft-scrollbar">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-dark-500/50" @if ($loading) wire:loading.class="opacity-25 cursor-not-allowed select-none" @endif>
            @if ($loading)
                <x-tallstack-ui::icon.others.loading class="absolute top-1/2 left-1/2 w-10 h-10 text-primary-500 dark:text-dark-300 animate-spin"
                                                     wire:loading="{{ $target }}" />
            @endif
            @if (!$withoutHeaders)
                <thead @class([
                    'uppercase',
                    'bg-gray-50 dark:bg-dark-600' => !$striped,
                    'bg-white dark:bg-dark-700' => $striped,
                ])>
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-700 dark:text-dark-200 px-3">
                            <a class="group inline-flex cursor-pointer truncate"
                               @if ($livewire && $sortable($header))
                                    wire:click="$set('sort', {column: '{{ $define($header)['column'] }}', direction: '{{ $define($header)['direction'] }}' })"
                                @endif>

                                {{ $header['label'] }}

                                @if ($livewire && $sortable($header) && $sorted($header))
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
                <tr @class(['bg-gray-50 dark:bg-dark-800/50' => $striped && $loop->index % 2 === 0])>
                    @foreach($headers as $header)
                        @php($row = str_replace('.', '_', $header['index']))
                        @if (isset(${$row}))
                            <td class="whitespace-nowrap py-4 text-sm px-3 text-gray-500 dark:text-dark-300">
                                {{ ${$row}($value) }}
                            </td>
                        @else
                            <td class="whitespace-nowrap py-4 text-sm px-3 text-gray-500 dark:text-dark-300">
                                {{ data_get($value, $header['index']) }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="whitespace-nowrap py-4 text-sm px-3 text-gray-500 dark:text-dark-300 col-span-full" colspan="100%">
                        {{ $placeholders['empty'] }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($paginate && $rows->hasPages())
        {{ $rows->onEachSide(1)->links($paginator) }}
    @endif
</div>
