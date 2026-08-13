@php
    $customization = $classes();
@endphp

<div class="{{ $customization['wrapper.first'] }}"
     x-data="tallstackui_dropdown(@js(!$static), @js($hover))"
     role="button"
     aria-haspopup="true"
     x-bind:aria-expanded="show">
    <div x-ref="dropdown"
         class="{{ $customization['wrapper.second'] }}"
         x-on:click.outside="show = false"
         @if ($hover)
             x-on:pointerenter="enter($event)"
         x-on:pointerleave="leave($event)"
         @endif
            {{ $attributes->only(['x-on:open', 'x-on:select']) }}>
        @if ($text)
            <button type="button"
                    class="{{ $customization['action.wrapper'] }}"
                    x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
                    aria-controls="dropdown-menu"
                    dusk="tallstackui_open_dropdown">
                <span class="{{ $customization['action.text'] }}">{{ $text }}</span>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('chevron-down')"
                                     internal
                                     class="{{ $customization['action.icon'] }}"
                                     x-bind:class="{ 'transform {{ $customization['action.icon-rotated'] }}': animate && show }" />
            </button>
        @elseif ($icon)
            <button type="button"
                    class="{{ $customization['action.wrapper'] }}"
                    x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
                    aria-controls="dropdown-menu"
                    dusk="tallstackui_open_dropdown">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :$icon
                                     internal
                                     class="{{ $customization['action.icon'] }}"
                                     x-bind:class="{ 'transform {{ $customization['action.icon-rotated'] }}': animate && show }" />
            </button>
        @else
            {!! $action !!}
        @endif
        <x-dynamic-component :component="TallStackUi::prefix('floating')"
                             scope="dropdown.floating"
                             :floating="$customization['floating.default']"
                             @class([
                                 $customization['floating.widths.xxs'],
                                 $customization['floating.widths.xs'],
                                 $customization['floating.widths.sm'],
                                 $customization['floating.widths.md'],
                                 $customization['floating.widths.lg'],
                                 $customization['floating.widths.xl'],
                                 $customization['floating.widths.2xl'],
                             ])
                             offset="5"
                             :$position
                             x-anchor="$refs.dropdown"
                             role="menu"
                             :data-tsui-dropdown-size="$size"
                             :data-tsui-dropdown-width="$width">
            <x-slot:transition>
                {!! $transitions() !!}
            </x-slot:transition>
            @if ($header)
                <div class="{{ $customization['header.wrapper'] }}">
                    {!! $header !!}
                </div>
            @endif
            <div class="{{ $customization['slot.wrapper'] }}">
                {!! $slot !!}
            </div>
        </x-dynamic-component>
    </div>
</div>
