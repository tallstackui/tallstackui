@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error = $computed && $errors->has($computed);
    $customize = tallstackui_personalization('select.native', $personalization());
@endphp

<div>
    @if ($label)
        <x-label :$error>
            {{ $label }} @if ($attributes['required'] && config('tallstackui.personalizations.input.required')) <i class="text-red-500 font-bold
            not-italic">*</i>
            @endif
        </x-label>
    @endif
    <select @if ($id) id="{{ $id }}" @endif {{ $attributes->class([
            $customize['input.class'],
            $customize['input.color'] => !$error,
            $customize['error'] => $error
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
