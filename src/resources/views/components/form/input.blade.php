@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$clearable :$id :$property :$error :$label :$hint :$invalidate :floatable="$attributes->get('floatable', false)">
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
        <div @class([$personalize['clearable.wrapper'], $personalize['clearable.paddings.' . $clearablePosition], '!pr-8' => $icon && $clearablePosition === 'right', '!pl-8' => $icon && $clearablePosition === 'left'])>
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                :icon="TallStackUi::icon('x-mark')"
                                x-on:click="$refs.input.value = ''"
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
        <input @if ($id) id="{{ $id }}" @endif
               type="{{ $attributes->get('type', 'text') }}"
               x-ref="input"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
               {{ $attributes->class([
                    'pr-3 pl-0' => $prefix, 
                    'pl-3 pr-0' => $suffix, 
                    $personalize['input.base'],
                    $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left') || $clearable && ($clearablePosition === null || $clearablePosition === 'left'),
                    $personalize['input.paddings.right'] => $icon && $position === 'right' || $clearable && $clearablePosition === 'right',
                    $personalize['input.paddings.clearable.left'] => $icon && $clearable && $clearablePosition === 'left',
                    $personalize['input.paddings.clearable.right'] => $icon && $clearable && $clearablePosition === 'right',
                ]) }}>
        @if ($suffix)
            <span @class(['ml-1 mr-2', $personalize['input.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</x-dynamic-component>
