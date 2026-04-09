@php
    $customization = $classes();
@endphp

<template x-teleport="body">
<div x-show="{{ $attributes->get('x-show', 'show') }}"
     x-cloak
     x-on:click.stop
     x-on:mousedown.stop
     x-on:click.outside="{{ $attributes->get('x-show', 'show') }} = false"
     x-on:keydown.escape.window="{{ $attributes->get('x-show', 'show') }} = false"
     x-intersect:leave="{{ $attributes->get('x-show', 'show') }} = false"
{{ $anchor() }}="{{ $attributes->get('x-anchor', '$refs.anchor') }} || $el"
{{ $attributes->whereStartsWith('x-on') }}
@if (!$ts_ui__flash)
    @if (count($attributes->whereStartsWith('x-transition')->getAttributes()) === 0 || $transition?->isEmpty())
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
    @elseif ($transition?->isNotEmpty())
        {{ $transition }}
    @else
        {!! $attributes->except(['x-show', 'x-anchor', 'class']) !!}
    @endif
@endif
x-init="(() => { const getAnchor = () => { try { return {{ $attributes->get('x-anchor', '$refs.anchor') }} } catch(e) { return null } }; const anchor = getAnchor(); if ($el.classList.contains('w-full') && anchor) { const setWidth = () => { const a = getAnchor(); if (a && a.offsetWidth) $el.style.width = a.offsetWidth + 'px' }; $watch('{{ $attributes->get('x-show', 'show') }}', v => { if (v) $nextTick(() => setWidth()) }); let _r; new MutationObserver(() => { cancelAnimationFrame(_r); _r = requestAnimationFrame(() => setWidth()) }).observe($el, { childList: true, subtree: true }); if ({{ $attributes->get('x-show', 'show') }}) $nextTick(() => setWidth()); Livewire?.hook?.('commit', ({succeed}) => { succeed(() => { $nextTick(() => { if ($el.isConnected && {{ $attributes->get('x-show', 'show') }}) setWidth() }) }) }) } if (anchor) { const overlay = anchor.closest('[x-data*=tallstackui_modal], [x-data*=tallstackui_slide]'); if (overlay) overlay.addEventListener('close', () => {{ $attributes->get('x-show', 'show') }} = false) } })()"
{{ $attributes->except(['floating', 'x-anchor'])->merge(['class' => $attributes->get('floating', $customization['wrapper']), 'data-floating' => true]) }}>
{{ $slot }}
{{ $footer }}
</div>
</template>
