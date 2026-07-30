@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([$customization['wrapper'], $colors['text']]) }}
     role="status">
    @if ($textual)
        <x-dynamic-component component="ts-ui::spinner.types.{{ $type }}"
                             :$size
                             :$customization
                             :$frames
                             :$interval>{{ $label !== false ? ($label ?? $slot) : '' }}</x-dynamic-component>
    @else
        <x-dynamic-component component="ts-ui::spinner.types.{{ $type }}"
                             :$size
                             :$customization />
        @if ($label !== false && ($label !== null || !empty(trim($slot->toHtml()))))
            <span @class([$customization['text.base'], $customization['text.sizes.' . $size]])>{{ $label ?? $slot }}</span>
        @endif
    @endif
    @if ($label === false || ($label === null && empty(trim($slot->toHtml()))))
        <span class="sr-only">{{ trans('ts-ui::messages.spinner.loading') }}</span>
    @endif
</div>
