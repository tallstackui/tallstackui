@php($customize = tasteui_personalization('form.radio', $customization($error)))

<x-taste-ui::wrappers.form.radio-toggle.wrapper :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="radio" {{ $attributes->class($customize['base']) }} @checked($checked)>
</x-taste-ui::wrappers.form.radio-toggle.wrapper>
