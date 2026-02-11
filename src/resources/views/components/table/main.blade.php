@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_table({!! $entangle !!}, @js($ids()), @js($selectable))"
     @if ($persistent) x-ref="persist" @endif>
    @if (is_string($header))
        <p class="{{ $customization['slots.header'] }}">{{ $header }}</p>
    @else
        {{ $header }}
    @endif
    @if (count((array) $rows) > 0 && $livewire && !is_null($filter))
        <div @class([
                $customization['filter.wrapper'],
                'justify-between' => isset($filter['quantity']) && isset($filter['search']),
                'justify-start'   => isset($filter['quantity']) && ! isset($filter['search']),
                'justify-end'     => ! isset($filter['quantity']) && isset($filter['search']),
            ])>
            @isset ($filter['quantity'])
                <div class="{{ $customization['filter.quantity'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('select.styled')"
                                         scope="table.select-styled"
                                         :label="data_get($placeholders, 'quantity')"
                                         :options="$quantity"
                                         wire:model.live="{{ $filter['quantity'] }}"
                                         required
                                         invalidate />
                </div>
            @endisset
            @isset ($filter['search'])
                <div class="{{ $customization['filter.search'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('input')"
                                         scope="table.input"
                                         :icon="TallStackUi::icon('magnifying-glass')"
                                         wire:model.live.debounce.500ms="{{ $filter['search'] }}"
                                         :placeholder="data_get($placeholders, 'search')"
                                         type="search"
                                         invalidate />
                </div>
            @endisset
        </div>
    @endif
    <div class="{{ $customization['wrapper'] }}">
        <div class="{{ $customization['table.wrapper'] }}">
            <table class="{{ $customization['table.base'] }}"
                   @if ($livewire && $loading) wire:loading.class="{{ $customization['loading.table'] }}" @endif>
                @if ($livewire && $loading)
                    <x-ts-ui::icon.generic.loading class="{{ $customization['loading.icon'] }}"
                                                   wire:loading="{{ $target }}" />
                @endif
                @if (!$headerless)
                    <thead @class(['uppercase', $customization['table.thead.normal'] => !$striped, $customization['table.thead.striped'] => $striped])>
                    <tr>
                        @if ($expandable)
                            <th @class(['w-8', $customization['table.th']])></th>
                        @endif
                        @if ($selectable)
                            <th @class(['w-6', $customization['table.th']]) wire:key="checkall-{{ implode(',', $ids()) }}">
                                <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                     scope="table.checkbox"
                                                     x-ref="checkbox"
                                                     x-on:click="all($el.checked, {{ \Illuminate\Support\Js::from($ids()) }})"
                                                     dusk="tallstackui_table_select_all"
                                                     sm />
                            </th>
                        @endif
                        @foreach ($headers as $header)
                            <th scope="col" class="{{ $customization['table.th'] }}">
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
                                                             class="{{ $customization['table.sort'] }}" />
                                    @endif
                                </a>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                @endif
                <tbody class="{{ $customization['table.tbody'] }}">
                @if (is_array($rows) && (count($rows) === 1 && empty($rows[0])))
                    <tr>
                        <td class="{{ $customization['empty'] }}" colspan="100%">
                            @if ($empty)
                                {{ $empty }}
                            @else
                                {{ data_get($placeholders, 'empty') }}
                            @endif
                        </td>
                    </tr>
                @else
                    @forelse ($rows as $key => $value)
                        @php
                            $id = md5(serialize($value).$key);
                        @endphp
                        <tr @class([
                            $customization['table.tr'],
                            'bg-gray-50 dark:bg-dark-800/50' => $striped && $loop->index % 2 === 0 && ! $highlighted($value),
                            $highlighted($value),
                        ]) @if ($livewire) wire:key="{{ $id }}" @endif>
                            @if ($expandable)
                                <td class="{{ $customization['table.td'] }}">
                                    @isset($sub_table)
                                        <button type="button"
                                                x-on:click="toggle('{{ $id }}')"
                                                class="{{ $customization['expandable.button'] }} cursor-pointer">
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon('chevron-right')"
                                                                 internal
                                                                 x-bind:class="expanded('{{ $id }}') ? 'rotate-90' : ''"
                                                                 class="h-4 w-4 transition-transform duration-200" />
                                        </button>
                                    @endisset
                                </td>
                            @endif
                            @if ($selectable)
                                <td class="{{ $customization['table.td'] }}">
                                    <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                         scope="table.checkbox"
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
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$customization['table.td'], 'cursor-pointer' => $clickable])>
                                        {{ ${"column_".$row}($value) }}
                                    </td>
                                @else
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$customization['table.td'], 'cursor-pointer' => $clickable])>
                                        {{ data_get($value, $header['index']) }}
                                    </td>
                                @endisset
                            @endforeach
                        </tr>
                        @if ($expandable)
                            @isset($sub_table)
                                <tr x-show="expanded('{{ $id }}')" x-cloak @if ($livewire) wire:key="sub-{{ $id }}"
                                    @endif class="{{ $customization['expandable.wrapper'] }}">
                                    <td colspan="100%" class="{{ $customization['expandable.content'] }}">
                                        {{ $sub_table($value) }}
                                    </td>
                                </tr>
                            @endisset
                        @endif
                    @empty
                        <tr>
                            <td class="{{ $customization['empty'] }}" colspan="100%">
                                @if ($empty)
                                    {{ $empty }}
                                @else
                                    {{ data_get($placeholders, 'empty') }}
                                @endif
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
    @if (is_string($footer))
        <p class="{{ $customization['slots.footer'] }}">{{ $footer }}</p>
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
