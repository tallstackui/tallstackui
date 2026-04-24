@php
    $customization = $classes();
@endphp

<div
    class="{{ $customization['item.wrapper'] }}"
    x-data="{ id: @js($identifier) }"
    x-init="if (!items.find(i => i.id === id)) items.push({ id, open: @js((bool) $open) })"
>
    <button
        type="button"
        x-on:click="toggle(id)"
        x-bind:aria-expanded="isOpen(id) ? 'true' : 'false'"
        x-bind:class="{
            '{{ $customization['item.trigger.open'] }}': isOpen(id),
            '{{ $customization['item.trigger.closed'] }}': !isOpen(id),
        }"
        class="{{ $customization['item.trigger.base'] }}"
        dusk="tallstackui_accordion_trigger_{{ $identifier }}"
    >
        @if ($content['trigger'])
            <span class="flex-1">{!! $content['trigger'] !!}</span>
        @else
            <span class="flex-1">{{ $title }}</span>
        @endif

        @if ($icon instanceof \Illuminate\View\ComponentSlot)
            <span
                x-bind:class="{ '{{ $customization['item.icon.rotated'] }}': isOpen(id) }"
                class="{{ $customization['item.icon.base'] }}"
            >
                {!! $icon->toHtml() !!}
            </span>
        @elseif (is_string($icon) && $icon !== '')
            <x-icon
                :name="$icon"
                x-bind:class="{ '{{ $customization['item.icon.rotated'] }}': isOpen(id) }"
                class="{{ $customization['item.icon.base'] }}"
            />
        @else
            <x-icon
                name="chevron-down"
                x-bind:class="{ '{{ $customization['item.icon.rotated'] }}': isOpen(id) }"
                class="{{ $customization['item.icon.base'] }}"
            />
        @endif
    </button>

    <div x-show="isOpen(id)" x-collapse x-cloak>
        <div class="{{ $customization['item.content'] }}" dusk="tallstackui_accordion_content_{{ $identifier }}">
            {{ $slot }}
        </div>
    </div>
</div>
