@php
    $customization = $classes();
@endphp

<div wire:ignore
     x-data="tallstackui_reaction({!! $entangle !!}, @js($content($id)), @js($position), @js($configurations['delay']), @js($configurations['balloon']), @js($configurations['hover']))"
     @if ($configurations['hover'])
         x-on:pointerenter="enter($event)"
         x-on:pointerleave="leave($event)"
     @endif>
    <button x-on:click="toggle($event)"
            dusk="tallstackui_reaction_button"
            x-ref="button"
            class="{{ $customization['wrapper.first'] }}"
            {{ $attributes->only('x-on:react') }}
            id="{{ $id }}">
        <div class="{{ $customization['wrapper.second'] }}">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                @foreach ($icons as $icon => $key)
                    @if ($loop->iteration <= 3)
                        <img class="{{ $customization['image'] }}"
                             src="https://fonts.gstatic.com/s/e/notoemoji/latest/{{ $key }}/512.{{ $extension }}">
                    @endif
                @endforeach
            @endif
        </div>
        @if ($quantity)
            @if (is_string($quantity))
                <p class="{{ $customization['quantity'] }}" x-text="quantity"></p>
            @else
                {{ $quantity }}
            @endif
        @endif
    </button>
</div>
