@php
    \TallStackUi\Foundation\Exceptions\MissingLivewireException::throwIf($livewire, 'table');
    $personalize = $classes();
@endphp

<div @if ($persistent && $id) id="{{ $id }}" @endif>
    @if (is_string($header))
        <p @class($personalize['slots.header'])>{{ $header }}</p>
    @else
        {{ $header }}
    @endif
    @if (count((array) $rows) > 0 && $livewire && !is_null($filter))
        <div @class([
                $personalize['filter'],
                'justify-between' => isset($filter['quantity']) && isset($filter['search']),
                'justify-start'   => isset($filter['quantity']) && ! isset($filter['search']),
                'justify-end'     => ! isset($filter['quantity']) && isset($filter['search']),
            ])>
            @isset ($filter['quantity'])
                <div class="w-1/4 sm:w-1/5">
                    <x-dynamic-component :component="TallStackUi::component('select.styled')"
                                         :label="$placeholders['quantity']"
                                         :options="$quantity"
                                         wire:model.live="{{ $filter['quantity'] }}"
                                         required
                                         invalidate />
                </div>
            @endisset
            @isset ($filter['search'])
                <div class="sm:w-1/5">
                    <x-dynamic-component :component="TallStackUi::component('input')"
                                         :icon="TallStackUi::icon('magnifying-glass')"
                                         wire:model.live.debounce.500ms="{{ $filter['search'] }}"
                                         :placeholder="$placeholders['search']"
                                         type="search"
                                         invalidate />
                </div>
            @endisset
        </div>
    @endif
    <div @class($personalize['wrapper'])>
        <div @class($personalize['table.wrapper'])>
            <table @class($personalize['table.base']) @if ($livewire && $loading) wire:loading.class="{{ $personalize['loading.table'] }}" @endif>
                @if ($livewire && $loading)
                    <x-tallstack-ui::icon.generic.loading class="{{ $personalize['loading.icon'] }}" wire:loading="{{ $target }}" />
                @endif
                @if (!$headerless)
                    <thead @class(['uppercase', $personalize['table.thead.normal'] => !$striped, $personalize['table.thead.striped'] => $striped])>
                        <tr>
                            @foreach ($headers as $header)
                                <th scope="col" @class($personalize['table.th'])>
                                    <a @if ($livewire && $sortable($header))
                                            class="inline-flex cursor-pointer truncate"
                                            wire:click="$set('sort', {column: '{{ $head($header)['column'] }}', direction: '{{ $head($header)['direction'] }}' })"
                                        @endif>
                                        @if ($header['unescaped'] ?? false)
                                            {!! $header['label'] ?? '' !!}
                                        @else
                                            {{ $header['label'] ?? '' }}
                                        @endif
                                        @if ($livewire && $sortable($header) && $sorted($header))
                                            <x-dynamic-component :component="TallStackUi::component('icon')"
                                                                :icon="TallStackUi::icon($head($header)['direction'] === 'desc' ? 'chevron-up' : 'chevron-down')"
                                                                class="ml-2 h-4 w-4" />
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody @class($personalize['table.tbody'])>
                @if (is_array($rows) && (count($rows) === 1 && empty($rows[0])))
                    <tr>
                        <td @class($personalize['empty']) colspan="100%">
                            {{ $placeholders['empty'] }}
                        </td>
                    </tr>
                @else
                    @forelse ($rows as $key => $value)
                        @if ($livewire)
                            @php
                                $this->loop = $loop;
                            @endphp
                        @endif
                        <tr @class(['bg-gray-50 dark:bg-dark-800/50' => $striped && $loop->index % 2 === 0]) @if ($livewire) wire:key="{{ md5(serialize($value).$key) }}" @endif>
                            @foreach($headers as $header)
                                @php
                                    $row = str_replace('.', '_', $header['index']);
                                @endphp
                                @isset(${"column_".$row})
                                    <td @class($personalize['table.td'])>
                                        {{ ${"column_".$row}($value) }}
                                    </td>
                                @else
                                    <td @class($personalize['table.td'])>
                                        {{ data_get($value, $header['index']) }}
                                    </td>
                                @endisset
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td @class($personalize['empty']) colspan="100%">
                                {{ $placeholders['empty'] }}
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
    @if (is_string($footer))
        <p @class($personalize['slots.footer'])>{{ $footer }}</p>
    @else
        {{ $footer }}
    @endif
    @if ($paginate && (!is_array($rows) && $rows->hasPages()))
        {{ $rows->onEachSide(1)->links($paginator, [
            'simplePagination' => $simplePagination,
            'scrollTo' => $persistent && $id ? '#'.$id : false,
        ]) }}
    @endif
</div>
