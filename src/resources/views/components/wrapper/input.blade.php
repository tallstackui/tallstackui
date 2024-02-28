@php($personalize = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])])

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$id :$label :$error :$invalidate />
    @endif
    <div x-ref="anchor" @class($personalize['wrapper'])>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
