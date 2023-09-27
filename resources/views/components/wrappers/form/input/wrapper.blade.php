@props(['computed', 'error', 'label', 'hint', 'validated', 'password' => null])

<div>
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div @class(config('tasteui.wrappers.form.input.div')) @if ($password) x-data="{ show : false }" @endif>
        {!! $slot !!}
    </div>
    @if ($hint && !$error)
        <x-hint :$hint />
    @endif
    @if($validated)
        <x-error :$computed :$error />
    @endif
</div>
