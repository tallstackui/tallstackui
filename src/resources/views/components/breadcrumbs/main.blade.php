@php
    $customization = $classes();
@endphp

@if ($items)
    <nav {{ $attributes->class([$customization['wrapper']]) }} aria-label="Breadcrumb">
        @if (isset($left)) {{ $left }} @endif
        <ol class="{{ $customization['list'] }}">
            @foreach ($items as $index => $item)
                @if ($index > 0)
                    <li class="{{ $customization['separator.wrapper'] }}">
                        @if ($separatorIsIcon)
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="$separator"
                                                 internal
                                                 @class([$customization['separator.icon'], $separatorClass]) />
                        @else
                            <span @class([$customization['separator.text'], $separatorClass])>{{ $separator }}</span>
                        @endif
                    </li>
                @endif
                <li class="{{ $customization['item.wrapper'] }}">
                    @if ($item['link'] ?? null)
                        <a href="{{ $item['link'] }}"
                           @class([$customization['item.link']])
                           @if ($item['tooltip'] ?? null) x-data x-tooltip="{{ $item['tooltip'] }}" @endif>
                            @if ($item['icon'] ?? null)
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="$item['icon']"
                                                     internal
                                                     class="{{ $customization['item.icon'] }}" />
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span @class([$customization['item.current']])
                              @if ($item['tooltip'] ?? null) x-data x-tooltip="{{ $item['tooltip'] }}" @endif>
                            @if ($item['icon'] ?? null)
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="$item['icon']"
                                                     internal
                                                     class="{{ $customization['item.icon'] }}" />
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
        @if (isset($right)) {{ $right }} @endif
    </nav>
@endif
