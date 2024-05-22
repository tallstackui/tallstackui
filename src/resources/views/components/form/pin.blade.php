@php
    [$property, $error, $id, $entangle] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    $hash = $livewire ? $__livewire->getId().'-'.$property : uniqid();
    $value = $attributes->get('value');
@endphp

@if ($livewire)
    <div hidden id="{{ $hash }}">@js($error)</div>
@elseif ($property)
    <div hidden id="{{ $hash }}">@js($errors->has($property))</div>
    <input hidden name="{{ $property }}" @if ($value) value="{{ $value }}" @endif>
@endif

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$label :$error />
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
             @js($value),
             @js($change($attributes, $__livewire ?? null, $livewire)))"
         x-on:paste="pasting = true; paste($event)" x-cloak wire:ignore>
        <div @class($personalize['wrapper']) x-ref="wrapper" {{ $attributes->only(['x-on:filled', 'x-on:clear']) }}>
            @if ($prefix)
                <input type="text"
                       value="{{ $prefix }}"
                       dusk="form_pin_prefix"
                       @class([
                           'w-[60px]',
                            $personalize['input.base'],
                            $personalize['input.color.background'],
                            $personalize['input.color.base'],
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
                           value="{{ $value[$index-1] ?? '' }}"
                       @endif
                       @class([
                           'w-[38px]',
                            $personalize['input.base'],
                            $personalize['input.color.background'],
                       ]) x-bind:class="{
                           '{{ $personalize['input.color.base'] }}': !error,
                           '{{ $personalize['input.color.error'] }}': error,
                       }" maxlength="1"
                       autocomplete="false"
                       x-on:keyup="keyup(@js($index))"
                       x-on:keyup.left="left(@js($index))"
                       x-on:keyup.right="right(@js($index))"
                       x-on:keyup.up="left(@js($index))"
                       x-on:keyup.down="right(@js($index))"
                       x-on:keyup.delete="backspace(@js($index))"
                       x-on:keydown.backspace="backspace(@js($index))" />
            @endforeach
            <template x-if="clear && model">
                <button class="cursor-pointer" x-on:click="erase();" dusk="form_pin_clear">
                    <x-dynamic-component :component="TallStackUi::component('icon')"
                                         :icon="TallStackUi::icon('x-circle')"
                                         solid
                                         @class($personalize['button']) />
                </button>
            </template>
        </div>
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
