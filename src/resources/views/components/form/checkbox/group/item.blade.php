<label for="{{ $reference }}-{{ $index }}" @class([
    $customization['item.base'],
    $customization['item.' . $variant],
    $colors['border'],
    $colors['solid'] => $variant === 'inline',
    $colors['background'] => $variant !== 'inline',
    $customization['item.disabled'] => $item['disabled'],
    $customization['item.error'] => $error,
])>
    <input type="{{ $type }}"
           id="{{ $reference }}-{{ $index }}"
           name="{{ $name }}"
           value="{{ $item['value'] }}"
           @checked(in_array((string) $item['value'], $selected, true))
           @disabled($item['disabled'])
           @if ($required && $type === 'radio') required @endif
           @if (!isset($option) && $shows['description'] && $item['description']) aria-describedby="{{ $reference }}-{{ $index }}-description" @endif
           {{ $attributes->except(['id', 'name', 'type', 'value', 'class'])->class([
                $customization['control.base'],
                $customization['control.shape'],
                $customization['control.sizes.' . $size],
                $colors['control'],
                $customization['control.spacing.' . $position] => $shows['control'],
                $customization['control.hidden'] => !$shows['control'],
           ]) }}>
    @isset($option)
        {{ $option($item) }}
    @else
        @if ($shows['image'] && $item['image'])
            <img src="{{ $item['image'] }}" alt="{{ $item['label'] }}" class="{{ $customization['content.image'] }}">
        @elseif ($shows['icon'] && $item['icon'])
            <x-dynamic-component :component="TallStackUi::prefix('icon')" :name="$item['icon']"
                                 @class([
                                     $customization['content.icon'],
                                     $colors['text'] => $variant !== 'inline',
                                     $customization['content.inline'] => $variant === 'inline',
                                 ]) />
        @endif
        <span class="{{ $customization['content.wrapper'] }}">
            <span class="{{ $customization['content.header'] }}">
                <span @class([
                    $customization['content.label'],
                    $colors['text'] => $variant !== 'inline',
                    $customization['content.inline'] => $variant === 'inline',
                ])>{{ $item['label'] }}</span>
                @if ($shows['badge'] && $item['badge'])
                    <x-dynamic-component :component="TallStackUi::prefix('badge')" :text="$item['badge']" :color="$color" sm />
                @endif
            </span>
            @if ($shows['description'] && $item['description'])
                <span id="{{ $reference }}-{{ $index }}-description"
                      @class([$customization['content.description'], $colors['muted']])>{{ $item['description'] }}</span>
            @endif
        </span>
        @if ($shows['aside'] && $item['aside'])
            <span @class([$customization['content.aside'], $colors['muted']])>{{ $item['aside'] }}</span>
        @endif
    @endisset
    @if ($shows['check'])
        <x-dynamic-component :component="TallStackUi::prefix('icon')" name="check-circle" @class([$customization['check'], $colors['text']]) />
    @endif
</label>
