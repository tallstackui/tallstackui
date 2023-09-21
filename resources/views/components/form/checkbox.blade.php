@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
    $wrapper  = $getBaseWrapper($error);
@endphp

<div>
    <div class="flex items-center">
        @if ($label && $position === 'left')
            <span @class($wrapper['left'])>
            <p @class($wrapper['text'])>{{ $label }}</p>
        </span>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class($getBaseLabel())>
            <input @if ($id) for="{{ $id }}" @endif type="checkbox" {{ $attributes->class($getBaseClass()) }} @checked($checked)>
        </label>
        @if ($label && $position === 'right')
            <span @class($wrapper['right'])>
            <p @class($wrapper['text'])>{{ $label }}</p>
        </span>
        @endif
    </div>
    <x-taste-ui::error :$computed :$error />
</div>
