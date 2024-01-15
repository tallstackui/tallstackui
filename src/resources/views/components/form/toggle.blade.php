@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, $livewire);
    $personalize = $classes();
    [$position, $alignment, $label] = $sloteable($label);

    // We remove any bg color classes from the wrapper if there
    // is an error to apply the red bg color to the input instead
    $personalize['wrapper.class'] = $error
        ? preg_replace('/\bbg-[a-zA-Z0-9-]+/', '', $personalize['background.class'])
        : $personalize['background.class'];
@endphp

<x-dynamic-component :component="TallStackUi::component('wrapper.radio')" :$id :$property :$error :$label :$position :$alignment :$invalidate>
    <div @class($personalize['wrapper'])>
        <input type="checkbox"
            @if ($id) id="{{ $id }}" @endif
            @if ($thematic) x-bind:value="darkTheme" x-on:click="darkTheme = !darkTheme" @endif
            {{ $attributes->class([$personalize['input.class'], $personalize['input.sizes.' . $size]]) }}>
        @if ($icons !== null)
        <x-dynamic-component :component="TallStackUi::component('icon')" :icon="$icons[0]" @class([
                                'right-0',
                                $personalize['icon.wrapper'], 
                                $personalize['icon.sizes.' . $size],
                                $personalize['icon.color.off'] => !$thematic,
                                $personalize['icon.thematic.light'] => $thematic,
                             ]) />
        <x-dynamic-component :component="TallStackUi::component('icon')" :icon="$icons[1]" @class([
                                'left-0',
                                $personalize['icon.wrapper'], 
                                $personalize['icon.sizes.' . $size],
                                $personalize['icon.color.on'] => !$thematic,
                                $personalize['icon.thematic.dark'] => $thematic,
                             ]) />
        @endif
        <div @class([
            $personalize['background.class'],
            $personalize['background.sizes.' . $size],
            $colors['background'],
            $personalize['error'] => $error
        ])></div>
    </div>
</x-dynamic-component>
