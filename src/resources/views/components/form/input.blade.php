@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
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
    <div @class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ])>
        @if ($prefix)
            <span @class(['ml-2 mr-1', $personalize['input.class.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input @if ($id) id="{{ $id }}" @endif
               type="{{ $attributes->get('type', 'text') }}"
               @if ($prefix || $suffix) autocomplete="{{ $attributes->get('autocomplete', 'off') }}" @endif
               {{ $attributes->class([
                    'pr-2 pl-0' => $prefix, 
                    'pl-2 pr-0' => $suffix, 
                    $personalize['input.class.base'],
                    $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
                    $personalize['input.paddings.right'] => $icon && $position === 'right'
                ]) }}>
        @if ($suffix)
            <span @class(['ml-1 mr-2', $personalize['input.class.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</x-dynamic-component>
