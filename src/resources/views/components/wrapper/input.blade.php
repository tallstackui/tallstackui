@php($personalize = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])])

<div>
    @if ($label)
        <x-label :$id :$label :$error :$invalidate/>
    @endif
    <div @class($personalize['wrapper']) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint/>
    @endif
    @if ($error)
        <x-error :$property/>
    @endif
</div>
