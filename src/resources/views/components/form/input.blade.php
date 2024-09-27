@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$clearable :$id :$property :$error :$label :$hint :$invalidate :floatable="$attributes->get('floatable', false)">
<div x-data="{ uniqueId: '{{ $id ?? uniqid() }}', showClearableIcon: '' }" x-init="$refs[`input_${uniqueId}`] = $el.querySelector('input'), showClearableIcon = $refs[`input_${uniqueId}`].value.length > 0">
    @if ($icon)
        <div @class([ $personalize['icon.wrapper'], $personalize['icon.paddings.' . $position]])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                :$icon
                                 :$error
                                 @class([
                                     $personalize['icon.size'],
                                     $personalize['error'] => $error,
                                     $personalize['icon.color'] => !$error && !$invalidate
                                 ]) />
        </div>
    @endif
    @if ($clearable)
        <div @class([
            $personalize['clearable.wrapper'], 
            $personalize['clearable.paddings.' . $position], 
            '!pr-8' => $icon && $position === 'right', 
            '!pl-8' => $icon && $position === 'left'
            ])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                :icon="TallStackUi::icon('x-mark')"
                                x-on:click="$refs[`input_${uniqueId}`].value = ''; showClearableIcon = false; $refs[`input_${uniqueId}`].focus()"
                                x-show="showClearableIcon"
                                @class([
                                    $personalize['clearable.size'],
                                    $personalize['clearable.color'] => !$error && !$invalidate,
                                ]) />
        </div>
    @endif
    <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ])>
        @if ($prefix)
            <span @class(['ml-2 mr-1', $personalize['input.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input x-bind:id="uniqueId"
               type="{{ $attributes->get('type', 'text') }}"
               x-ref="input_${uniqueId}"
               x-on:input="showClearableIcon = $refs[`input_${uniqueId}`].value.length > 0"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
               {{ $attributes->class([
                    'pr-3 pl-0' => $prefix, 
                    'pl-3 pr-0' => $suffix, 
                    $personalize['input.base'],
                    $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left') || $clearable && ($position === null || $position === 'left'),
                    $personalize['input.paddings.right'] => $icon && $position === 'right' || $clearable && $position === 'right',
                    $personalize['input.paddings.clearable.left'] => $icon && $clearable && $position === 'left',
                    $personalize['input.paddings.clearable.right'] => $icon && $clearable && $position === 'right',
                ]) }}>
        @if ($suffix)
            <span @class(['ml-1 mr-2', $personalize['input.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</div>
</x-dynamic-component>
