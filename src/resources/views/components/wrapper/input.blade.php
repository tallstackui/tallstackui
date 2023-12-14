@php
    $personalize = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])];
    $error = $wire && $errors->has($wire);
@endphp

<div>
    @if ($label)
        <x-label for="{{ $id }}" :$label :$error/>
    @endif
    <div @class($personalize['wrapper']) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error && $validate)
        <x-error :$wire/>
    @endif
</div>
