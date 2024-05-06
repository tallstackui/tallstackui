@php
    $personalize = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])];
@endphp

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::component('label')" :$id :$label :$error :$invalidate />
    @endif
    <div @if ($attributes->get('floatable', false)) x-ref="anchor" @endif @class($personalize['wrapper'])>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::component('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::component('error')" :$property />
    @endif
</div>
