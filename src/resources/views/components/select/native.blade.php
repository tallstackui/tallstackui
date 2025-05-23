@php
    $personalize = $classes();
@endphp

<div>
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')" :$label :$error />
    @endif
    <select {{ $attributes->class([
            $personalize['wrapper'],
            $personalize['input.wrapper'],
            $personalize['input.base'],
            $personalize['input.color.base'] => !$error,
            $personalize['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['error'] => $error
        ]) }}>
        @forelse ($options as $option)
            @if (!empty($selectable) && is_array($option[$selectable['value']]))
                <optgroup label="{{ $option[$selectable['label']] }}">
                    @foreach ($option[$selectable['value']] as $children)
                        @php
                            $value = (string) (!empty($selectable) ? $children[$selectable['value']] : $children);
                        @endphp
                        <option value="{{ $value }}" @selected(!$livewire && $value === (string) $attributes->get('value'))>{{ !empty($selectable) ? $children[$selectable['label']] : $children }}</option>
                    @endforeach
                </optgroup>
            @else
                @php
                    $value = (string) (!empty($selectable) ? $option[$selectable['value']] : $option);
                @endphp
                <option value="{{ $value }}" @selected(!$livewire && $value === (string) $attributes->get('value'))>{{ !empty($selectable) ? $option[$selectable['label']] : $option }}</option>
            @endif
        @empty
            {{ $slot }}
        @endforelse
    </select>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" :$hint />
    @endif
    @if ($error)
        <x-dynamic-component :component="TallStackUi::prefix('error')" :$property />
    @endif
</div>
