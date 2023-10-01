@props(['computed', 'error', 'label', 'position' => 'left', 'id' => null])

<div>
    <div @class(config('tasteui.wrappers.form.radio-toggle.wrapper'))>
        @if ($label && $position === 'left')
        <span @class([config('tasteui.wrappers.form.radio-toggle.label.span'), 'mr-1'])>
            <p @class([
                    config('tasteui.wrappers.form.radio-toggle.label.p'),
                    config('tasteui.wrappers.form.radio-toggle.label.error') => $error,
                ])>{{ $label }}</p>
        </span>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class(config('tasteui.wrappers.form.radio-toggle.slot'))>
            {!! $slot !!}
        </label>
        @if ($label && $position === 'right')
        <span @class([config('tasteui.wrappers.form.radio-toggle.label.span'), 'ml-1'])>
            <p @class([
                    config('tasteui.wrappers.form.radio-toggle.label.p'),
                    config('tasteui.wrappers.form.radio-toggle.label.error') => $error,
                ])>{{ $label }}</p>
        </span>
        @endif
    </div>
    <x-error :$computed :$error />
</div>
