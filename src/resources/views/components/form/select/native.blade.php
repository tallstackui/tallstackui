@php
    $customization = $classes();
@endphp

<div>
    @if ($label && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('label')" scope="form.select-native.label" :$label :$error />
    @endif
    <select {{ $attributes->class([
            $customization['wrapper'],
            $customization['input.wrapper'],
            $customization['input.base'],
            $customization['input.color.base'] => !$error,
            $customization['input.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $customization['input.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $customization['error'] => $error && ! $side,
            $customization['input.round.left'] => $side === 'left',
            $customization['input.round.right'] => $side === 'right',
            $customization['input.borderless'] => $side,
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
    @if ($hint && !$error && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="form.select-native.hint" :$hint />
    @endif
    @if ($error && !$side)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="form.select-native.error" :$property />
    @endif
</div>
