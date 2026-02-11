@php
    $customization = $classes();
@endphp

<div class="{{ $customization['wrapper.first'] }}"
     x-data="tallstackui_dropdown(@js(!$static))"
     role="button"
     aria-haspopup="true"
     x-bind:aria-expanded="show">
    <div x-ref="dropdown"
         class="{{ $customization['wrapper.second'] }}"
         x-on:click.outside="show = false"
            {{ $attributes->only(['x-on:open', 'x-on:select']) }}>
        @if ($text)
            <div class="{{ $customization['action.wrapper'] }}">
                <span class="{{ $customization['action.text'] }}">{{ $text }}</span>
                <button type="button"
                        x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
                        aria-controls="dropdown-menu"
                        dusk="tallstackui_open_dropdown">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('chevron-down')"
                                         internal
                                         class="{{ $customization['action.icon'] }}"
                                         x-bind:class="{ 'transform rotate-180': animate && show }" />
                </button>
            </div>
        @elseif ($icon)
            <div class="{{ $customization['action.wrapper'] }}">
                <button type="button"
                        x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
                        aria-controls="dropdown-menu"
                        dusk="tallstackui_open_dropdown">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :$icon
                                         internal
                                         class="{{ $customization['action.icon'] }}"
                                         x-bind:class="{ 'transform rotate-180': animate && show }" />
                </button>
            </div>
        @else
            {!! $action !!}
        @endif
        <x-dynamic-component :component="TallStackUi::prefix('floating')"
                             scope="dropdown.floating"
                             :floating="$customization['floating.default']"
                             :class="$customization['floating.class']"
                             offset="5"
                             :$position
                             x-anchor="$refs.dropdown"
                             role="menu">
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
