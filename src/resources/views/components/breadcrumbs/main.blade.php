@php
    $customization = $classes();
@endphp

@if ($items)
    <nav {{ $attributes->class([$customization['wrapper']]) }} aria-label="Breadcrumb">
        @if (isset($left)) {{ $left }} @endif
        <ol class="{{ $customization['list.class'] }} {{ $customization['list.sizes.' . $size] }}">
            @foreach ($items as $index => $item)
                @if ($index > 0)
                    <li class="{{ $customization['separator.wrapper'] }}">
                        @if (str_contains($separator, 'icon:'))
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="str_replace('icon:', '', $separator)"
                                                 internal
                                                 @class([$customization['separator.icon.class'], $customization['separator.icon.sizes.' . $size], $separatorClass]) />
                        @else
                            <span @class([$customization['separator.text.class'], $customization['separator.text.sizes.' . $size], $separatorClass])>{{ $separator }}</span>
                        @endif
                    </li>
                @endif
                <li class="{{ $customization['item.wrapper'] }}">
                    @if ($item['link'] ?? null)
                        <a href="{{ $item['link'] }}"
                           @class([$customization['item.link.class'], $customization['item.link.sizes.' . $size]])
                           @if ($item['tooltip'] ?? null) x-data x-tooltip="{{ $item['tooltip'] }}" @endif>
                            @if ($item['icon'] ?? null)
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="$item['icon']"
                                                     internal
                                                     class="{{ $customization['item.icon.class'] }} {{ $customization['item.icon.sizes.' . $size] }}" />
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span @class([$customization['item.current.class'], $customization['item.current.sizes.' . $size]])
                              @if ($item['tooltip'] ?? null) x-data x-tooltip="{{ $item['tooltip'] }}" @endif>
                            @if ($item['icon'] ?? null)
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="$item['icon']"
                                                     internal
                                                     class="{{ $customization['item.icon.class'] }} {{ $customization['item.icon.sizes.' . $size] }}" />
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
