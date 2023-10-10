<div class="flex items-start justify-center" x-data="{ show : false, animate : @js($animate) }">
    <div class="relative inline-block text-left" x-on:click.outside="show = false">
        @if ($text)
            <div class="inline-flex w-full gap-x-1.5">
                <span class="text-sm text-gray-700">{{ $text }}</span>
                <x-icon name="chevron-down"
                        class="h-5 w-5 cursor-pointer text-gray-400 transition"
                        x-on:click="show = !show"
                        x-bind:class="{ 'transform rotate-180': animate && show }"
                />
            </div>
        @elseif ($icon)
            <div class="inline-flex w-full gap-x-1.5">
                <x-icon :$icon
                        class="h-5 w-5 cursor-pointer text-gray-400 transition"
                        x-on:click="show = !show"
                        x-bind:class="{ 'transform rotate-180': animate && show }"
                />
            </div>
        @else
            {!! $action !!}
        @endif
        <div x-show="show"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-50"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-50"
             x-cloak
            @class([
                'absolute z-10 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
                'right-0 origin-top-right' => ! $right,
                'left-0 origin-top-left' => $right,
            ]) role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="px-1 py-1" role="none">
                @if ($header)
                    <div class="my-1 mx-2">
                        {!! $header !!}
                    </div>
                @endif
                {!! $slot !!}
            </div>
        </div>
    </div>
</div>
