@php
    $customization = $classes();
@endphp

@if ($livewire)
    <div hidden id="{{ $hash }}">@js($error)</div>
@elseif ($property)
    <div hidden id="{{ $hash }}">@js($errors->has($property))</div>
    <input hidden name="{{ $property }}" @if ($attributes->has('value')) value="{{ $attributes->get('value') }}" @endif>
@endif

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')" scope="form.pin.label" :$label :$error />
    @endif
    <div x-data="tallstackui_formPin(
             {!! $entangle !!},
             @js($hash),
             @js($length),
             @js($clear),
             @js($numbers),
             @js($letters),
             @js($livewire),
             @js($property),
             @js($attributes->get('value')),
             @js($change),
             @js($smart))"
         x-on:paste="pasting = true; paste($event)" x-cloak wire:ignore.self>
        <div class="{{ $customization['wrapper'] }}"
             x-ref="wrapper" {{ $attributes->only(['x-on:filled', 'x-on:clear']) }}>
            @if ($prefix)
                <input type="text"
                       value="{{ $prefix }}"
                       dusk="form_pin_prefix"
                       @class([
                            $customization['input.size.prefix'],
                            $customization['input.base'],
                            $customization['input.color.background'],
                            $customization['input.color.base'],
                       ]) disabled />
            @endif
            @foreach (range(1, $length) as $index)
                <input type="text"
                       id="pin-{{ $hash }}-{{ $index }}"
                       dusk="pin-{{ $index }}"
                       @if ($mask) x-mask="{{ $mask }}" @endif
                       @if ($livewire)
                           value="{{ isset($__livewire->{$property}) ? (strval($__livewire->{$property})[$index-1] ?? '') : '' }}"
                       @elseif ($property)
                           value="{{ $attributes->get('value')[$index-1] ?? '' }}"
                       @endif
                       @class([
                            $customization['input.size.base'],
                            $customization['input.base'],
                            $customization['input.color.background'],
                       ]) x-bind:class="{
                           '{{ $customization['input.color.base'] }}': !error,
                           '{{ $customization['input.color.error'] }}': @js($invalidate ?? false) === false && error,
                       }" maxlength="1"
                       autocomplete="false"
                       @if ($numbers)
                           inputmode="numeric"
                       @endif
                       @required($attributes->get('required', false))
                       x-on:focus="setTimeout(() => $el.selectionStart = $el.selectionEnd = $el.value.length, 0)"
                       x-on:keyup="keyup(@js($index))"
                       x-on:keyup.left="left(@js($index))"
                       x-on:keyup.right="right(@js($index))"
                       x-on:keyup.up="left(@js($index))"
                       x-on:keyup.down="right(@js($index))"
                       x-on:keyup.delete="backspace($event, @js($index))"
                       x-on:keyup.backspace="backspace($event, @js($index))" />
            @endforeach
            <template x-if="clear && model">
                <button class="cursor-pointer" x-on:click="erase();" dusk="form_pin_clear">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-circle')"
                                         solid
                                         internal
                                         class="{{ $customization['button'] }}" />
                </button>
            </template>
        </div>
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="form.pin.hint" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="form.pin.error" :$property />
    @endif
</div>
