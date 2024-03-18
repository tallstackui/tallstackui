<div class="relative">
    <div class="mb-2 inline-block rounded-lg border border-gray-300 bg-gray-200 px-1.5 py-0.5 text-xs font-medium dark:border-dark-700 dark:bg-dark-800 dark:text-white" 
         style="margin-inline-start: calc({{ $percent }}% - 1.25rem);">
        {{ $percent }}%</div>
    <div @class(['flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700', $personalize['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="{{ $percent }}"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class(['flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500', $colors['background']])
             style="width: {{ $percent }}%"></div>
    </div>
</div>
