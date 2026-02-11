@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint
                     :$invalidate :floatable="$attributes->get('floatable', false)">
    @if ($addon)
        <div @class([
            $customization['input.addon.wrapper'],
            $customization['input.color.base'] => !$error,
            $customization['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $customization['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $customization['input.addon.error'] => $error,
        ])>
            @if ($prefixed)
                <div @class([$customization['input.addon.button.base'], $customization['input.addon.button.left']])>
                    {{ $prefix }}
                </div>
            @endif
    @endif
    <div @class([
            'relative flex-1 ring-0! focus-within:ring-0!' => $addon,
            $customization['input.wrapper'],
            $customization['input.addon.round.left'] => $prefixed,
            $customization['input.addon.round.right'] => $suffixed,
            $customization['input.color.base'] => !$error,
            $customization['input.color.background'] => !$addon && !$attributes->get('disabled') && !$attributes->get('readonly'),
            $customization['input.color.disabled'] => !$addon && ($attributes->get('disabled') || $attributes->get('readonly')),
            $customization['error'] => $error
        ])>
        @if ($icon)
            <div @class([$customization['icon.wrapper'], $customization['icon.paddings.' . $position]])>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :$icon
                                     :$error
                                     internal
                                     @class([
                                         $customization['icon.size'],
                                         $customization['error'] => $error,
                                         $customization['icon.color'] => !$error && !$invalidate
                                     ]) />
            </div>
        @endif
        @if ($clearable)
            <div x-data="tallstackui_formInputClearable(@js($ref))"
                 @class([$customization['clearable.wrapper'], $customization['clearable.padding'], '!pr-8' => $icon && $position === 'right']) x-show="clearable">
                <button type="button" class="cursor-pointer" dusk="tallstackui_form_input_clearable">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('x-mark')"
                                         x-on:click="clear()"
                                         internal
                                         @class([
                                             $customization['clearable.size'],
                                             $customization['clearable.color'] => !$error && !$invalidate,
                                         ]) />
                </button>
            </div>
        @endif
        @if (!$prefixed)
            @if ($prefix instanceof \Illuminate\View\ComponentSlot)
                <div {{ $prefix->attributes->merge(['class' => $customization['input.slot']]) }}>
                    {{ $prefix }}
                </div>
            @elseif (is_string($prefix))
                <span @class(['ml-2 mr-1', $customization['input.slot'], $customization['error'] => $error])>{{ $prefix }}</span>
            @endif
        @endif
        <input @if ($id) id="{{ $id }}" @endif
        type="{{ $attributes->get('type', 'text') }}"
               x-ref="{{ $attributes->get('x-ref', $ref) }}"
               @if ($stripZeros) x-data="tallstackui_formInputStripZeros(@js($ref))" @endif
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
                {{ $attributes->class([
                     $customization['input.base'],
                     $customization['input.paddings.prefix'] => $prefix && !$prefixed,
                     $customization['input.paddings.suffix'] => $suffix && !$suffixed,
                     $customization['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
                     $customization['input.paddings.right'] => $icon && $position === 'right' || $icon && $clearable,
                     $customization['input.paddings.clearable'] => $icon && $clearable && $position === 'right',
                 ]) }}>
        @if (!$suffixed)
            @if ($suffix instanceof \Illuminate\View\ComponentSlot)
                <div {{ $suffix->attributes->merge(['class' => $customization['input.slot']]) }}>
                    {{ $suffix }}
                </div>
            @elseif (is_string($suffix))
                <span @class(['ml-1 mr-2', $customization['input.slot'], $customization['error'] => $error])>{{ $suffix }}</span>
            @endif
        @endif
    </div>
    @if ($addon)
            @if ($suffixed)
                <div @class([$customization['input.addon.button.base'], $customization['input.addon.button.right']])>
                    {{ $suffix }}
                </div>
            @endif
        </div>
    @endif
</x-dynamic-component>
