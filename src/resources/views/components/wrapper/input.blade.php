@php
    $customization = ['wrapper' => $attributes->get('wrapper', $classes()['wrapper'])];
@endphp

<div>
    @if ($label instanceof \Illuminate\View\ComponentSlot)
        {{ $label }}
    @elseif ($label && is_string($label))
        <x-dynamic-component :component="TallStackUi::prefix('label')" scope="wrapper.input.label" :$id :$label :$error :$invalidate />
    @endif
    <div @if ($attributes->get('floatable', false)) x-ref="anchor" @endif class="{{ $customization['wrapper'] }}">
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="wrapper.input.hint" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="wrapper.input.error" :$property />
    @endif
</div>
