@php
    $personalize = $classes();
@endphp

<x-dynamic-component :component="TallStackUi::prefix('wrapper.input')" :$id :$property :$error :$label :$hint :$invalidate class="flex">
    @if ($side === 'left')
        {{ $selector }}
    @endif
    <div class="{{ $personalize['input.wrapper'] }}">
        @if ($prefix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $prefix->attributes->merge(['class' => $personalize['input.slot']]) }}>
                {{ $prefix }}
            </div>
        @elseif (is_string($prefix))
            <span @class(['ml-2 mr-1', $personalize['input.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input @if ($id) id="{{ $id }}" @endif
               type="{{ $attributes->get('type', 'text') }}"
               x-ref="{{ $attributes->get('x-ref', 'input') }}"
               {{ $attributes->class([
                    $personalize['input.base'],
                    $personalize['input.color.base'],
                    $personalize['input.color.background'],
                    $personalize['input.round.left'] => $side === 'left',
                    $personalize['input.round.right'] => $side === 'right'
               ]) }}>
        @if ($suffix instanceof \Illuminate\View\ComponentSlot)
            <div {{ $suffix->attributes->merge(['class' => $personalize['input.slot']]) }}>
                {{ $suffix }}
            </div>
        @elseif (is_string($suffix))
            <span @class(['ml-1 mr-2', $personalize['input.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
    @if ($side === 'right')
        {{ $selector }}
    @endif
</x-dynamic-component>
