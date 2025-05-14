@php
    $personalize = $classes();
@endphp

<div x-data="tallstackui_table({!! $entangle !!}, @js($ids()), @js($selectable))" @if ($persistent) x-ref="persist" @endif>
    @if (is_string($header))
        <p class="{{ $personalize['slots.header'] }}">{{ $header }}</p>
    @else
        {{ $header }}
    @endif
    @if (count((array) $rows) > 0 && $livewire && !is_null($filter))
        <div @class([
                $personalize['filter.wrapper'],
                'justify-between' => isset($filter['quantity']) && isset($filter['search']),
                'justify-start'   => isset($filter['quantity']) && ! isset($filter['search']),
                'justify-end'     => ! isset($filter['quantity']) && isset($filter['search']),
            ])>
            @isset ($filter['quantity'])
                <div class="{{ $personalize['filter.quantity'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('select.styled')"
                                         :label="data_get($placeholders, 'quantity')"
                                         :options="$quantity"
                                         wire:model.live="{{ $filter['quantity'] }}"
                                         required
                                         invalidate />
                </div>
            @endisset
            @isset ($filter['search'])
                <div class="{{ $personalize['filter.search'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         :icon="TallStackUi::icon('magnifying-glass')"
                                         wire:model.live.debounce.500ms="{{ $filter['search'] }}"
                                         :placeholder="data_get($placeholders, 'search')"
                                         type="search"
                                         invalidate />
                </div>
            @endisset
        </div>
    @endif
    <div class="{{ $personalize['wrapper'] }}">
        <div class="{{ $personalize['table.wrapper'] }}">
            <table class="{{ $personalize['table.base'] }}" @if ($livewire && $loading) wire:loading.class="{{ $personalize['loading.table'] }}" @endif>
                @if ($livewire && $loading)
                    <x-tallstack-ui::icon.generic.loading class="{{ $personalize['loading.icon'] }}" wire:loading="{{ $target }}" />
                @endif
                @if (!$headerless)
                    <thead @class(['uppercase', $personalize['table.thead.normal'] => !$striped, $personalize['table.thead.striped'] => $striped])>
                        <tr>
                            @if ($selectable)
                                <th @class(['w-6', $personalize['table.th']]) wire:key="checkall-{{ implode(',', $ids()) }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                         x-ref="checkbox"
                                                         x-on:click="all($el.checked, {{ \Illuminate\Support\Js::from($ids()) }})"
                                                         dusk="tallstackui_table_select_all"
                                                         sm />
                                </th>
                            @endif
                            @foreach ($headers as $header)
                                <th scope="col" class="{{ $personalize['table.th'] }}">
                                    <a @if ($livewire && $sortable($header))
                                            class="inline-flex cursor-pointer truncate"
                                            wire:click="$set('sort', {column: '{{ $head($header)['column'] }}', direction: '{{ $head($header)['direction'] }}' })"
                                        @endif>
                                        @if ($header['unescaped'] ?? false)
                                            {!! $header['label'] ?? '' !!}
                                        @else
                                            {{ $header['label'] ?? '' }}
                                        @endif
                                        @if ($livewire && $sortable($header))
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon($sorted($header) ? ($head($header)['direction'] === 'desc' ? 'chevron-up' : 'chevron-down') : 'chevron-up-down')"
                                                                 internal
                                                                 class="{{ $personalize['table.sort'] }}" />
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody class="{{ $personalize['table.tbody'] }}">
                @if (is_array($rows) && (count($rows) === 1 && empty($rows[0])))
                    <tr>
                        <td class="{{ $personalize['empty'] }}" colspan="100%">
                            {{ data_get($placeholders, 'empty') }}
                        </td>
                    </tr>
                @else
                    @forelse ($rows as $key => $value)
                        @php
                            $id = md5(serialize($value).$key);
                        @endphp
                        <tr @class([$personalize['table.tr'], 'bg-gray-50 dark:bg-dark-800/50' => $striped && $loop->index % 2 === 0]) @if ($livewire) wire:key="{{ $id }}" @endif>
                            @if ($selectable)
                                <td class="{{ $personalize['table.td'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                         id="checkbox-{{ $key }}"
                                                         :attributes="$modifier()"
                                                         value="{{ data_get($value, $selectableProperty) }}"
                                                         x-on:click="select($el.checked, {{ \Illuminate\Support\Js::from($value) }})"
                                                         sm />
                                </td>
                            @endif
                            @foreach($headers as $header)
                                @php
                                    $row = str_replace('.', '_', $header['index']);
                                    $url = $href($value);
                                    $clickable = $link !== null;
                                @endphp
                                @isset(${"column_".$row})
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$personalize['table.td'], 'cursor-pointer' => $clickable])>
                                        {{ ${"column_".$row}($value) }}
                                    </td>
                                @else
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$personalize['table.td'], 'cursor-pointer' => $clickable])>
                                        {{ data_get($value, $header['index']) }}
                                    </td>
                                @endisset
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td class="{{ $personalize['empty'] }}" colspan="100%">
                                {{ data_get($placeholders, 'empty') }}
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
    @if (is_string($footer))
        <p class="{{ $personalize['slots.footer'] }}">{{ $footer }}</p>
    @else
        {{ $footer }}
    @endif
    @if ($paginate && (!is_array($rows) && $rows->hasPages()))
        {{ $rows->onEachSide($onEachSide)->links($paginator, [
            'simplePagination' => $simplePagination,
            'scrollTo' => $persistent ?? false,
        ]) }}
    @endif
</div>
