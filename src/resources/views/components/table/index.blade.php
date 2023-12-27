@php($livewire = isset($__livewire))

<div>
    @if ($livewire && $filter)
        <div @class([
                'mb-4 flex items-end',
                'justify-between' => $filters['quantity'] && $filters['search'],
                'justify-end' => ! $filters['quantity'] || ! $filters['search'],
            ])>
            @if ($filters['quantity'])
                <div class="w-1/5">
                    <x-select.styled :label="$placeholders['quantity']"
                                     :options="$quantity"
                                     wire:model.live="{{ $filters['quantity'] }}"
                                     invalidate />
                </div>
            @endif
            @if ($filters['search'])
                <div class="sm:w-1/5">
                    <x-input wire:model.live.debounce.500ms="{{ $filters['search'] }}"
                             icon="magnifying-glass"
                             :placeholder="$placeholders['search']"
                             type="search"
                             invalidate />
                </div>
            @endif
        </div>
    @endif
    <div class="soft-scrollbar dark:ring-dark-600 overflow-auto rounded-lg shadow ring-1 ring-gray-300">
        <table class="dark:divide-dark-500/50 min-w-full divide-y divide-gray-200" @if ($livewire && $loading) wire:loading.class="cursor-not-allowed select-none opacity-25" @endif>
            @if ($livewire && $loading)
                <x-tallstack-ui::icon.others.loading class="text-primary-500 dark:text-dark-300 absolute left-1/2 top-1/2 h-10 w-10 animate-spin"
                                                     wire:loading="{{ $target }}" />
            @endif
            @if (!$headerless)
                <thead @class(['uppercase', 'bg-gray-50 dark:bg-dark-600' => !$striped, 'bg-white dark:bg-dark-700' => $striped])>
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            <a class="inline-flex cursor-pointer truncate"
                               @if ($livewire && $sortable($header))
                                    wire:click="$set('sort', {column: '{{ $head($header)['column'] }}', direction: '{{ $head($header)['direction'] }}' })"
                                @endif>

                                {{ $header['label'] ?? '' }}

                                @if ($livewire && $sortable($header) && $sorted($header))
                                    <x-icon :name="$head($header)['direction'] === 'desc' ? 'chevron-up' : 'chevron-down'"  class="ml-2 h-4 w-4" />
                                @endif
                            </a>
                        </th>
                    @endforeach
                </tr>
                </thead>
            @endif
            <tbody class="dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white">
            @forelse ($rows as $key => $value)
                <tr @class(['bg-gray-50 dark:bg-dark-800/50' => $striped && $loop->index % 2 === 0])>
                    @foreach($headers as $header)
                        @php($row = str_replace('.', '_', $header['index']))
                        @if (isset(${$row}))
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ ${$row}($value) }}
                            </td>
                        @else
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ data_get($value, $header['index']) }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="dark:text-dark-300 col-span-full whitespace-nowrap px-3 py-4 text-sm text-gray-500" colspan="100%">
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
