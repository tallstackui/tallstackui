@php
    $personalize = $classes();
@endphp

<button type="button"
    @class([
        $personalize['button'],
        'rounded-full' => !$square,
        'rounded-lg' => $square,
        'flex-col' => $label
    ])>
    <x-dynamic-component :component="TallStackUi::component('icon')"
            :icon="TallStackUi::icon($icon)"
            @class([
                $personalize['icon'],
                'mb-1' => $label
            ]) />
            
    @if ($label)
        <span @class($personalize['label'])>{{ $label }}</span>
        <span class="sr-only">{{ $label }}</span>
    @endif
</button>