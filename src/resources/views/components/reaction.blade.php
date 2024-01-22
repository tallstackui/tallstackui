@php
    \TallStackUi\Foundation\Exceptions\MissingLivewireException::throwIf($livewire, 'reaction');
    $entangle = $bind($attributes, null, $livewire)[3];
    $personalize = $classes();
    $extension = $animated === true ? 'gif' : 'png';
@endphp

<div wire:ignore>
  <button x-data="tallstackui_reaction({!! $entangle !!}, @js($content()), @js($position))"
          x-on:click="show = !show"
          x-on:mouseover="show = true"
          x-ref="button"
          @class($personalize['wrapper.first'])>
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
