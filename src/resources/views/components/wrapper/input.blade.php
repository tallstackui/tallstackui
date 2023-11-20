@php($personalize = tallstackui_personalization('wrapper.input', $personalization()))

<div>
    @if ($label)
        <x-label @if($id) for="{{ $id }}" @endif :$label :$error/>
    @endif
    <div @class($personalize['wrapper']) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error && $validate)
        <x-error :$computed :$error/>
    @endif
</div>
