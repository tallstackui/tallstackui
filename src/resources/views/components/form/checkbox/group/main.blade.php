@php
    $customization = $classes();
@endphp

<fieldset {{ $attributes->only('class')->class([$customization['wrapper.base']]) }}>
    @if ($legend['text'])
        <legend @class([$customization['wrapper.legend'], $customization['wrapper.legend-error'] => $error])>
            {{ $legend['text'] }}
            @if ($legend['asterisk'])
                <span class="{{ $customization['wrapper.asterisk'] }}">*</span>
            @endif
        </legend>
    @endif
    <div @class([
        $customization['container.' . $variant],
        $customization['columns.' . $columns] => in_array($variant, ['card', 'panel']),
    ])>
        @foreach ($options as $item)
            @include('ts-ui::components.form.checkbox.group.item', ['item' => $item, 'index' => $loop->index])
        @endforeach
    </div>
    @if ($hint && !$error)
        <x-dynamic-component :component="TallStackUi::prefix('hint')" scope="form.checkbox.group.hint" :$hint />
    @endif
    @if ($validate)
        <x-dynamic-component :component="TallStackUi::prefix('error')" scope="form.checkbox.group.error" :$property />
    @endif
</fieldset>
