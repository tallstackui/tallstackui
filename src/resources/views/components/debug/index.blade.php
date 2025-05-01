@props(["code" => null])

<div>
    {!! $slot !!}
    @if ($code)
        <x-dynamic-component
            component="tallstack-ui::icon.generic.code-bracket-square"
            data-position="auto"
            x-tooltip="{!! $code !!}"
            class="h-4 w-4 text-red-500 dark:text-white"
        />
    @endif
</div>
