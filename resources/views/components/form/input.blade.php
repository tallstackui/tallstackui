<div>
    @if ($label)
        <x-label :$label />
    @endif
    <div class="relative mt-2 rounded-md shadow-sm">
        @if ($icon)
            <div @class([
                    'pointer-events-none absolute inset-y-0 flex items-center',
                    'left-0 pl-3'  => $position === null || $position === 'left',
                    'right-0 pr-3' => $position === 'right',
                ])>
                <x-icon :name="$icon" solid class="h-5 w-5 text-gray-500" />
            </div>
        @endif

        <input @if ($id) id="{{ $id }}" @endif
            {{ $attributes->class([
                    'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300',
                    'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition',
                    'pl-10' => $icon && ($position === null || $position === 'left'),
                ])
            }}>
    </div>
    @if ($hint)
        <span class="mt-2 text-sm text-secondary-500">
            {{ $hint }}
        </span>
    @endif
</div>
