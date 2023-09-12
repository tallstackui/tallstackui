<div class="flex items-center">
    @if ($label && $position === 'left')
        <span class="mr-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
    @endif
    <label @if ($id) for="{{ $id }}" @endif class="relative inline-flex cursor-pointer items-center">
        <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            'absolute mx-0.5 my-auto inset-y-0 left-0.5 rounded-full border-0',
            'appearance-none translate-x-0 transform transition ease-in-out duration-200 cursor-pointer shadow',
            'checked:bg-none peer focus:ring-0 focus:ring-offset-0 focus:outline-none bg-white checked:text-white',
            'w-3 h-3 checked:translate-x-2.5'   => $size === 'sm',
            'w-3.5 h-3.5 checked:translate-x-4' => $size === 'md',
            'w-4 h-4 checked:translate-x-4'     => $size === 'lg',
        ]) }} @checked($checked)>
        <div @class([
            'block rounded-full cursor-pointer transition ease-in-out duration-100 peer-focus:ring-2',
            'peer-focus:ring-offset-2 group-focus:ring-2 group-focus:ring-offset-2 bg-secondary-200',
            'peer-checked:bg-primary-600 peer-focus:ring-primary-600 group-focus:ring-primary-600',
            'h-4 w-7'  => $size === 'sm',
            'h-5 w-9'  => $size === 'md',
            'h-6 w-10' => $size === 'lg',
        ])></div>
    </label>
    @if ($label && $position === 'right')
        <span class="ml-2 text-sm">
            <p class="font-medium text-gray-700">{{ $label }}</p>
        </span>
    @endif
</div>
