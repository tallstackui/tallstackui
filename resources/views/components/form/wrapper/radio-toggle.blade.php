@props(['computed', 'error', 'label', 'position' => 'left', 'id' => null])

<div>
    <div class="flex items-center">
        @if ($label && $position === 'left')
            <span class="mr-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
        @endif
        <label @if ($id) for="{{ $id }}" @endif @class(config('tasteui.wrappers.form.radio-toggle.span'))>
            {!! $slot !!}
        </label>
        @if ($label && $position === 'right')
            <span class="ml-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
        @endif
    </div>
    <x-taste-ui::error :$computed :$error />
</div>
