@php
    [$property, $error] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$label :$error />
    @endif
    <select {{ $attributes->class([
            $personalize['wrapper'],
            $personalize['input.wrapper'],
            $personalize['input.base'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ]) }}>
        @forelse ($options as $option)
            @php
                $value = (string) ($select ? $option[$selectable['value']] : $option);
            @endphp
            <option value="{{ $value }}" @selected(!$livewire && $value === (string) $attributes->get('value'))>{{ $select ? $option[$selectable['label']] : $option }}</option>
        @empty
            {{ $slot }}
        @endforelse
    </select>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
