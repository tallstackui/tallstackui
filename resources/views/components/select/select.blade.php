@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $errors->has($computed);
    $customize = tallstackui_personalization('select', $customization());
@endphp

<div>
    @if ($label)
        <x-label :$label :$error/>
    @endif
    <select @if ($id) id="{{ $id }}" @endif {{ $attributes->class([$customize['wrapper'], $customize['error'] => $error]) }}>
        @forelse ($options as $option)
            <option value="{{ $select ? $option[$selectable['value']] : $option }}">{{ $select ? $option[$selectable['label']] : $option }}</option>
        @empty
            {{ $slot }}
        @endforelse
    </select>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    <x-error :$computed :$error/>
</div>
