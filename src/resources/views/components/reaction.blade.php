@php
    \TallStackUi\Foundation\Exceptions\MissingLivewireException::throwIf($livewire, 'reaction');
    $entangle = $bind($attributes, livewire: $livewire)[3];
    $personalize = $classes();
    $extension = $animated === true ? 'gif' : 'png';
    $id = $this->getId();
@endphp

<div wire:ignore>
  <button x-data="tallstackui_reaction({!! $entangle !!}, @js($content($id)), @js($position))"
          x-on:click="show = !show"
          dusk="tallstackui_reaction_button"
          x-ref="button"
          @class($personalize['wrapper.first'])
          {{ $attributes->only('x-on:react') }}
          id="{{ $id }}">
    <div @class($personalize['wrapper.second'])>
      @if ($slot->isNotEmpty())
        {{ $slot }}
      @else
        @foreach ($icons as $icon => $key)
          @if ($loop->iteration <= 3)
            <img @class($personalize['image'])
                 src="https://fonts.gstatic.com/s/e/notoemoji/latest/{{ $key }}/512.{{ $extension }}">
          @endif
        @endforeach
      @endif
    </div>
    @if ($quantity)
      @if (is_string($quantity))
        <p @class($personalize['quantity']) x-text="quantity"></p>
      @else
        {{ $quantity }}
      @endif
    @endif
  </button>
</div>
