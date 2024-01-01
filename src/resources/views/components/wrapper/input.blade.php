@php($personalize = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])])

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$id :$label :$error :$invalidate/>
    @endif
    <div @class($personalize['wrapper']) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint/>
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property/>
    @endif
</div>
