@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate>
    <div x-data="tallstackui_formTextArea(@js($personalize['count.max']))">
        <div @class([
            $personalize['input.wrapper'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error,
        ])>
            <textarea @if ($id) id="{{ $id }}" @endif
                    x-ref="textarea"
                    @if ($count) x-on:keyup="counter()" @endif
                    @if ($resizeAuto) x-on:input="resize()" @endif
                    {{ $attributes->class([
                        'resize-none' => !$resize && !$resizeAuto,
                        $personalize['input.base'],
                    ])->merge(['rows' => 3]) }}>{{ $attributes->get('value', $slot) }}</textarea>
        </div>
        @if ($count)
            <span @class($personalize['count.base']) x-ref="counter"></span>
        @endif
    </div>
</x-dynamic-component>
