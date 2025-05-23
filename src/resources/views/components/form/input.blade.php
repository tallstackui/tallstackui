@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate :floatable="$attributes->get('floatable', false)">
    @if ($icon)
        <div @class([$personalize['icon.wrapper'], $personalize['icon.paddings.' . $position]])>
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :$icon
                                 :$error
                                 internal
                                 @class([
                                     $personalize['icon.size'],
                                     $personalize['error'] => $error,
                                     $personalize['icon.color'] => !$error && !$invalidate
                                 ]) />
        </div>
    @endif
    @if ($clearable)
        <div x-data="tallstackui_formInputClearable(@js($ref))" @class([ $personalize['clearable.wrapper'], $personalize['clearable.padding'], '!pr-8' => $icon && $position === 'right']) x-show="clearable">
            <button type="button" class="cursor-pointer" dusk="tallstackui_form_input_clearable">
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('x-mark')"
                                     x-on:click="clear()"
                                     internal
                                     @class([
                                         $personalize['clearable.size'],
                                         $personalize['clearable.color'] => !$error && !$invalidate,
                                     ]) />
            </button>
        </div>
    @endif
    <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ])>
        @if ($prefix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $prefix->attributes->merge(['class' => $personalize['input.slot']]) }}>
                {{ $prefix }}
            </div>
        @elseif (is_string($prefix))
            <span @class(['ml-2 mr-1', $personalize['input.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input @if ($id) id="{{ $id }}" @endif
               type="{{ $attributes->get('type', 'text') }}"
               x-ref="{{ $attributes->get('x-ref', $ref) }}"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
               {{ $attributes->class([
                    $personalize['input.base'],
                    $personalize['input.paddings.prefix'] => $prefix,
                    $personalize['input.paddings.suffix'] => $suffix,
                    $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
                    $personalize['input.paddings.right'] => $icon && $position === 'right' || $icon && $clearable,
                    $personalize['input.paddings.clearable'] => $icon && $clearable && $position === 'right',
                ]) }}>
        @if ($suffix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $suffix->attributes->merge(['class' => $personalize['input.slot']]) }}>
                {{ $suffix }}
            </div>
        @elseif (is_string($suffix))
            <span @class(['ml-1 mr-2', $personalize['input.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</x-dynamic-component>
