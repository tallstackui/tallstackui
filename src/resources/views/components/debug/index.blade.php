@props(['code' => null])

<div>
    {!! $slot !!}
    @if ($code)
        <x-dynamic-component component="tallstack-ui::icon.generic.code-bracket-square"
                             data-position="auto"
                             x-tooltip="{!! $code !!}"
                             class="w-4 h-4 text-red-500 dark:text-white" />
    @endif
</div>
