@php
    $customization = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    @if ($left || $right)
        <div @class([
            $customization['input.wrapper.first'],
            $customization['input.color.base'] => !$error,
            $customization['input.wrapper.error'] => $error,
        ])>
            @if ($left)
                <div class="flex-none">
                    {{ $left }}
                </div>
            @endif
    @endif
    <div @class([
            'relative flex-1 ring-0! focus-within:ring-0!',
            $customization['input.wrapper.second'],
            $customization['input.wrapper.round.left'] => $left,
            $customization['input.wrapper.round.right'] => $right,
            $customization['input.color.base'] => !$error,
            $customization['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $customization['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
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
        @if ($prefix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $prefix->attributes->merge(['class' => $customization['input.slot']]) }}>
                {{ $prefix }}
            </div>
        @elseif (is_string($prefix))
            <span @class(['ml-2 mr-1', $customization['input.slot'], $customization['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input @if ($id) id="{{ $id }}" @endif
        type="{{ $attributes->get('type', 'text') }}"
               x-ref="{{ $attributes->get('x-ref', $ref) }}"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
                {{ $attributes->class([
                     $customization['input.base'],
                     $customization['input.paddings.prefix'] => $prefix,
                     $customization['input.paddings.suffix'] => $suffix,
                     $customization['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
                     $customization['input.paddings.right'] => $icon && $position === 'right' || $icon && $clearable,
                     $customization['input.paddings.clearable'] => $icon && $clearable && $position === 'right',
                 ]) }}>
        @if ($suffix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $suffix->attributes->merge(['class' => $customization['input.slot']]) }}>
                {{ $suffix }}
            </div>
        @elseif (is_string($suffix))
            <span @class(['ml-1 mr-2', $customization['input.slot'], $customization['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
    @if ($left || $right)
            @if ($right)
                <div class="flex-none">
                    {{ $right }}
                </div>
            @endif
        </div>
    @endif
</x-dynamic-component>
