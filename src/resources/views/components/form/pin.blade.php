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
             @js($smart),
             @js($disabled),
             @js($readonly))"
         x-on:paste="paste($event)" x-cloak wire:ignore.self>
        <div class="{{ $customization['wrapper'] }}"
             x-ref="wrapper" {{ $attributes->only(['x-on:filled', 'x-on:clear']) }}>
            @if ($prefix)
                <input type="text"
                       value="{{ $prefix }}"
                       dusk="form_pin_prefix"
                       @class([
                            $customization['input.size.prefix'],
                            $customization['input.base'],
                            $customization['input.spacing'],
                            $customization['input.rounding'],
                            $customization['input.color.background'],
                            $customization['input.color.base'],
                            $customization['input.locked'] => $locked,
                       ]) readonly tabindex="-1" aria-hidden="true" />
            @endif
            @foreach (range(1, $length) as $index)
                <input type="{{ $type }}"
                       id="pin-{{ $hash }}-{{ $index }}"
                       dusk="pin-{{ $index }}"
                       @if ($livewire)
                           value="{{ isset($__livewire->{$property}) ? (strval($__livewire->{$property})[$index-1] ?? '') : '' }}"
                       @elseif ($property)
                           value="{{ $attributes->get('value')[$index-1] ?? '' }}"
                       @endif
                       @disabled($disabled)
                       @readonly($readonly)
                       @class([
                            $customization['input.size.base'],
                            $customization['input.base'],
                            $customization['input.spacing'] => !$group || in_array($index, $lasts),
                            $customization['input.rounding'] => !$group,
                            $customization['input.group.base'] => $group,
                            $customization['input.group.first'] => $group && in_array($index, $firsts),
                            $customization['input.group.last'] => $group && in_array($index, $lasts),
                            $customization['input.group.joined'] => $group && !in_array($index, $firsts),
                            $customization['input.color.background'],
                            $customization['input.locked'] => $locked,
                       ]) x-bind:class="{
                           '{{ $customization['input.color.base'] }}': !error,
                           '{{ $customization['input.color.error'] }}': @js($invalidate ?? false) === false && error,
                       }"
                       autocomplete="{{ $numbers && $index === 1 ? 'one-time-code' : 'off' }}"
                       @if ($numbers)
                           inputmode="numeric"
                       @endif
                       @required($attributes->get('required', false))
                       x-on:focus="setTimeout(() => $el.selectionStart = $el.selectionEnd = $el.value.length, 0)"
                       x-on:input="type(@js($index))"
                       x-on:keydown.left.prevent="left(@js($index))"
                       x-on:keydown.right.prevent="right(@js($index))"
                       x-on:keydown.up.prevent="left(@js($index))"
                       x-on:keydown.down.prevent="right(@js($index))"
                       x-on:keydown.delete="backspace($event, @js($index))"
                       x-on:keydown.backspace="backspace($event, @js($index))" />
                @if (in_array($index, $separators))
                    <span @class([$customization['separator'], $customization['input.locked'] => $locked])
                          dusk="pin-separator-{{ $index }}"
                          aria-hidden="true">{{ $symbol }}</span>
                @endif
            @endforeach
            <template x-if="clear && model">
                <button class="cursor-pointer" x-on:click="erase();" @disabled($locked) dusk="form_pin_clear">
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
    @if ($validate)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="form.pin.error" :$property />
    @endif
</div>
