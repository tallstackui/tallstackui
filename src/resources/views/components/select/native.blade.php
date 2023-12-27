@php
    [$property, $error] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
@endphp

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <select {{ $attributes->class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.base'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ]) }}>
        @forelse ($options as $option)
            @php($value = (string) ($select ? $option[$selectable['value']] : $option))
            <option value="{{ $value }}" @selected(!$livewire && $value === (string) $attributes->get('value'))>{{ $select ? $option[$selectable['label']] : $option }}</option>
        @empty
            {{ $slot }}
        @endforelse
    </select>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error)
        <x-error :$property/>
    @endif
</div>
