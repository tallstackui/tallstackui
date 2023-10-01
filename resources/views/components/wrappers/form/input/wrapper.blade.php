@props(['computed', 'error', 'label', 'hint', 'validate' => true, 'password' => null])

<div>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div @class(config('tasteui.wrappers.form.input')) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint />
    @endif
    @if ($validate)
        <x-error :$computed :$error />
    @endif
</div>
