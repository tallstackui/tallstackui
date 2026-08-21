@php
    $customization = $classes();
@endphp

<div x-data="tallstackui_table({!! $entangle !!}, @js($selectable))"
     @if ($anchored) id="{{ $anchor }}" @endif
     @if ($selectable) data-ids='@json($ids())' @endif
     @if ($persistent === true) x-ref="persist" @endif>
    @if (is_string($header))
        <p class="{{ $customization['slots.header'] }}">{{ $header }}</p>
    @else
        {{ $header }}
    @endif
    @if (count((array) $rows) > 0 && !is_null($filter))
        <div @class([
                $customization['filter.wrapper'],
                $customization['filter.wrapper-with-search-and-quantity'] => isset($filter['quantity']) && isset($filter['search']),
                $customization['filter.wrapper-quantity-only']   => isset($filter['quantity']) && ! isset($filter['search']),
                $customization['filter.wrapper-search-only']     => ! isset($filter['quantity']) && isset($filter['search']),
            ])>
            @isset ($filter['quantity'])
                <div class="{{ $customization['filter.quantity'] }}"
                     @if (!$livewire) x-on:select.capture="navigate({ '{{ $filter['quantity'] }}': $event.detail.select }, @js($anchor))" @endif>
                    @if ($livewire)
                        <x-dynamic-component :component="TallStackUi::prefix('select.styled')"
                                             scope="table.select-styled"
                                             :label="data_get($placeholders, 'quantity')"
                                             :options="$quantity"
                                             wire:model.live="{{ $filter['quantity'] }}"
                                             required
                                             invalidate />
                    @else
                        <x-dynamic-component :component="TallStackUi::prefix('select.styled')"
                                             scope="table.select-styled"
                                             :label="data_get($placeholders, 'quantity')"
                                             :options="$quantity"
                                             :value="$quantifying"
                                             required
                                             invalidate />
                    @endif
                </div>
            @endisset
            @isset ($filter['search'])
                <div class="{{ $customization['filter.search'] }}"
                     @if (!$livewire) x-on:input.debounce.500ms="navigate({ '{{ $filter['search'] }}': $event.target.value }, @js($anchor))" @endif>
                    @if ($livewire)
                        <x-dynamic-component :component="TallStackUi::prefix('input')"
                                             scope="table.input"
                                             :icon="TallStackUi::icon('magnifying-glass')"
                                             wire:model.live.debounce.500ms="{{ $filter['search'] }}"
                                             :placeholder="data_get($placeholders, 'search')"
                                             type="search"
                                             invalidate />
                    @else
                        <x-dynamic-component :component="TallStackUi::prefix('input')"
                                             scope="table.input"
                                             :icon="TallStackUi::icon('magnifying-glass')"
                                             :value="$searching"
                                             :placeholder="data_get($placeholders, 'search')"
                                             type="search"
                                             invalidate />
                    @endif
                </div>
            @endisset
        </div>
    @endif
    <div class="{{ $customization['wrapper'] }}">
        <div class="{{ $customization['table.wrapper'] }}">
            <table class="{{ $customization['table.base'] }}"
                   @if ($livewire && $loading) wire:loading.class="{{ $customization['loading.table'] }}" @endif>
                @if ($livewire && $loading)
                    @if ($spinner)
                        <div class="{{ $customization['loading.indicator'] }}"
                             wire:loading="{{ $target }}">
                            <x-dynamic-component :component="TallStackUi::prefix('spinner')"
                                                 :type="$spinner" />
                        </div>
                    @else
                        <x-ts-ui::icon.generic.loading class="{{ $customization['loading.icon'] }}"
                                                       wire:loading="{{ $target }}" />
                    @endif
                @endif
                @if (!$headerless)
                    <thead @class([$customization['table.th-uppercase'], $customization['table.thead.normal'] => !$striped, $customization['table.thead.striped'] => $striped])>
                    <tr>
                        @if ($expandable)
                            <th @class([$customization['table.th-checkbox-width'], $customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact])></th>
                        @endif
                        @if ($selectable)
                            <th @class([$customization['table.th-actions-width'], $customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact]) wire:key="checkall-{{ implode(',', $ids()) }}">
                                <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                     scope="table.checkbox"
                                                     x-ref="checkbox"
                                                     x-on:change="all($el.checked, {{ \Illuminate\Support\Js::from($ids()) }})"
                                                     dusk="tallstackui_table_select_all"
                                                     sm />
                            </th>
                        @endif
                        @foreach ($headers as $header)
                            <th scope="col" @class([$customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact, $customization['table.align.'.$alignment($header)]])>
                                <a @if ($sortable($header))
                                       class="{{ $customization['table.th-sort-wrapper'] }} cursor-pointer"
                                       @if ($livewire)
                                           wire:click="$set('sort', {column: '{{ $head($header)['column'] }}', direction: '{{ $head($header)['direction'] }}' })"
                                       @else
                                           href="{{ $sorting($header) }}"
                                       @endif
                                        @endif>
                                    @if ($header['unescaped'] ?? false)
                                        {!! $header['label'] ?? '' !!}
                                    @else
                                        {{ $header['label'] ?? '' }}
                                    @endif
                                    @if ($sortable($header))
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
                        <td @class([$customization['empty'] => ! $compact, $customization['empty-compact'] => $compact]) colspan="100%">
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
                            $customization['row.striped'] => $striped && $loop->index % 2 === 0 && ! $highlighted($value),
                            $highlighted($value),
                        ]) @if ($livewire) wire:key="{{ $id }}" @endif>
                            @if ($expandable)
                                <td @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact])>
                                    @isset($sub_table)
                                        <button type="button"
                                                x-on:click="toggle('{{ $id }}')"
                                                @class([$customization['expandable.button'], 'cursor-pointer'])>
                                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                                 :icon="TallStackUi::icon('chevron-right')"
                                                                 internal
                                                                 x-bind:class="expanded('{{ $id }}') ? '{{ $customization['expandable.rotated'] }}' : ''"
                                                                 class="{{ $customization['expandable.icon'] }}" />
                                        </button>
                                    @endisset
                                </td>
                            @endif
                            @if ($selectable)
                                <td @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact])>
                                    <x-dynamic-component :component="TallStackUi::prefix('checkbox')"
                                                         scope="table.checkbox"
                                                         id="checkbox-{{ $key }}"
                                                         :attributes="$modifier()"
                                                         value="{{ data_get($value, $selectableProperty) }}"
                                                         x-on:change="select({{ \Illuminate\Support\Js::from($value) }})"
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
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact, $customization['table.align.'.$alignment($header)], $customization['cell-clickable'] => $clickable])>
                                        {{ ${"column_".$row}($value) }}
                                    </td>
                                @else
                                    <td @if ($clickable) x-on:click.prevent="redirect(@js($url), @js($blank))" @endif @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact, $customization['table.align.'.$alignment($header)], $customization['cell-clickable'] => $clickable])>
                                        {{ data_get($value, $header['index']) }}
                                    </td>
                                @endisset
                            @endforeach
                        </tr>
                        @if ($expandable)
                            @isset($sub_table)
                                <tr x-show="expanded('{{ $id }}')" x-cloak @if ($livewire) wire:key="sub-{{ $id }}"
                                    @endif class="{{ $customization['expandable.wrapper'] }}">
                                    <td colspan="100%" @class([$customization['expandable.content'] => ! $compact, $customization['expandable.content-compact'] => $compact])>
                                        {{ $sub_table($value) }}
                                    </td>
                                </tr>
                            @endisset
                        @endif
                    @empty
                        <tr>
                            <td @class([$customization['empty'] => ! $compact, $customization['empty-compact'] => $compact]) colspan="100%">
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
    @if ($paginate && $paginated)
        {{ $pagination->links($paginatorView(), $paginating) }}
    @endif
</div>
