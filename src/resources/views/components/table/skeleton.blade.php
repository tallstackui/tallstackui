@php
    $customization = $classes();
@endphp

<div aria-busy="true" aria-live="polite" {{ $attributes->class([$customization['skeleton.animation']]) }}>
    @if (! is_null($filter))
        <div @class([
            $customization['filter.wrapper'],
            $customization['filter.wrapper-with-search-and-quantity'] => isset($filter['quantity']) && isset($filter['search']),
            $customization['filter.wrapper-quantity-only'] => isset($filter['quantity']) && ! isset($filter['search']),
            $customization['filter.wrapper-search-only'] => ! isset($filter['quantity']) && isset($filter['search']),
        ])>
            @isset($filter['quantity'])
                <div class="{{ $customization['filter.quantity'] }}">
                    <div @class([$customization['skeleton.bar'], $customization['skeleton.filter.quantity']])></div>
                </div>
            @endisset
            @isset($filter['search'])
                <div class="{{ $customization['filter.search'] }}">
                    <div @class([$customization['skeleton.bar'], $customization['skeleton.filter.search']])></div>
                </div>
            @endisset
        </div>
    @endif
    <div class="{{ $customization['wrapper'] }}">
        <div class="{{ $customization['table.wrapper'] }}">
            <table class="{{ $customization['table.base'] }}">
                @if (! $headerless)
                    <thead @class([$customization['table.th-uppercase'], $customization['table.thead.normal'] => ! $striped, $customization['table.thead.striped'] => $striped])>
                    <tr>
                        @if ($expandable)
                            <th @class([$customization['table.th-checkbox-width'], $customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact])></th>
                        @endif
                        @if ($selectable)
                            <th @class([$customization['table.th-actions-width'], $customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact])>
                                <div @class([$customization['skeleton.bar'], $customization['skeleton.checkbox']])></div>
                            </th>
                        @endif
                        @forelse ($headers as $header)
                            <th scope="col" @class([$customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact, $customization['table.align.'.$alignment($header)]])>
                                @if ($header['unescaped'] ?? false)
                                    {!! $header['label'] ?? '' !!}
                                @else
                                    {{ $header['label'] ?? '' }}
                                @endif
                            </th>
                        @empty
                            @for ($column = 1; $column <= $columns; $column++)
                                <th scope="col" @class([$customization['table.th'] => ! $compact, $customization['table.th-compact'] => $compact])>
                                    <div @class([$customization['skeleton.bar'], $customization['skeleton.header']])></div>
                                </th>
                            @endfor
                        @endforelse
                    </tr>
                    </thead>
                @endif
                <tbody class="{{ $customization['table.tbody'] }}">
                @for ($line = 1; $line <= $lines; $line++)
                    <tr @class([
                        $customization['table.tr'],
                        $customization['row.striped'] => $striped && $line % 2 !== 0,
                    ])>
                        @if ($expandable)
                            <td @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact])>
                                <div @class([$customization['skeleton.bar'], $customization['skeleton.expand']])></div>
                            </td>
                        @endif
                        @if ($selectable)
                            <td @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact])>
                                <div @class([$customization['skeleton.bar'], $customization['skeleton.checkbox']])></div>
                            </td>
                        @endif
                        @for ($column = 1; $column <= $columns; $column++)
                            <td @class([$customization['table.td'] => ! $compact, $customization['table.td-compact'] => $compact])>
                                <div @class([$customization['skeleton.bar'], $customization['skeleton.cell']])></div>
                            </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
        @if ($paginate)
            <div class="{{ $customization['skeleton.paginate.wrapper'] }}">
                <div @class([$customization['skeleton.bar'], $customization['skeleton.paginate.bar']])></div>
            </div>
        @endif
    </div>
</div>
