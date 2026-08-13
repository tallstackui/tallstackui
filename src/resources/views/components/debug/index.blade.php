@props(['code' => null])

<div>
    {!! $slot !!}
    @if ($code)
        <x-dynamic-component component="ts-ui::icon.generic.code-bracket-square"
                             data-position="auto"
                             x-data
                             x-tooltip="{!! $code !!}"
                             data-tooltip-delay="flash"
                             class="w-4 h-4 text-red-500 dark:text-white" />
    @endif
</div>
