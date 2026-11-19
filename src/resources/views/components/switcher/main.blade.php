@php
    $customization = $classes();
@endphp

<label dusk="tallstackui_switcher"
       {{ $attributes->only('class')->class([
           $customization['wrapper.base'],
           $customization['wrapper.flip'] => $flip,
           $customization['wrapper.locked'] => $locked,
       ]) }}>
    <input type="checkbox"
           role="switch"
           x-data
           x-on:change="$el.dispatchEvent(new CustomEvent('switch', { detail: { value: $el.checked } }))"
           @if ($checked) checked @endif
           @if ($readonly) x-on:click.prevent aria-readonly="true" @endif
           {{ $attributes->except('class')->class([$customization['input']]) }}>
    <span dusk="tallstackui_switcher_on"
          @class([
              $customization['content.base'],
              $customization['content.sizes.'.$size],
              $customization['content.reverse'] => $position === 'right',
              $customization['content.transition'] => !$ts_ui__flash,
              $customization['on.'.$effect],
          ])>
        @if ($iconOn)
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="$iconOn"
                                 internal
                                 class="{{ $customization['icon.sizes.'.$size] }}" />
        @endif
        {{ $on }}
    </span>
    <span dusk="tallstackui_switcher_off"
          @class([
              $customization['content.base'],
              $customization['content.sizes.'.$size],
              $customization['content.reverse'] => $position === 'right',
              $customization['content.transition'] => !$ts_ui__flash,
              $customization['off.'.$effect],
          ])>
        @if ($iconOff)
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="$iconOff"
                                 internal
                                 class="{{ $customization['icon.sizes.'.$size] }}" />
        @endif
        {{ $off }}
    </span>
</label>
