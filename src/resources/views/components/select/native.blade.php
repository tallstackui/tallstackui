@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $personalize = tallstackui_personalization('select.native', $personalization());
@endphp

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <select {{ $attributes->class([
            $personalize['input.class'],
            $personalize['input.color'] => !$error,
            $personalize['error'] => $error
        ]) }}>
        @forelse ($options as $option)
            <option value="{{ $select ? $option[$selectable['value']] : $option }}">{{ $select ? $option[$selectable['label']] : $option }}</option>
        @empty
            {{ $slot }}
        @endforelse
    </select>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error && $computed)
        <x-error :$computed :$error/>
    @endif
</div>
