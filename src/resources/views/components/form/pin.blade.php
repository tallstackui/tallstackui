@php
    $personalize = $classes();
    $wire = $wireable($attributes);
    $property = $wire?->value();
    $livewire = $wire && $property;
    $error = !$invalidate && $livewire && $errors->has($property);
    $hash = $livewire
        ? $__livewire->getId().'-'.$property
        : uniqid();
@endphp

@if ($livewire)
    <div hidden id="{{ $hash }}">@js($error)</div>
@endif

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <div x-data="tallstackui_formPin(
             @entangleable($attributes),
             @js($hash),
             @js($length),
             @js($clear),
             @js($numbers),
             @js($letters),
         )" x-on:paste="pasting = true; paste($event)" x-cloak wire:ignore>
        <div @class($personalize['wrapper'])>
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
                       @if ($livewire) value="{{ $wire && isset($__livewire->{$property}) ? (strval($__livewire->{$property})[$index-1] ?? '') : '' }}" @endif
                       @class([
                           'w-[38px]',
                            $personalize['input.base'],
                            $personalize['input.color.background'],
                       ]) x-bind:class="{
                           '{{ $personalize['input.color.base'] }}': !error,
                           '{{ $personalize['input.color.error'] }}': error,
                       }" maxlength="1"
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
                    <x-icon name="x-circle" solid @class($personalize['button']) />
                </button>
            </template>
        </div>
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error)
        <x-error :$wire/>
    @endif
</div>
