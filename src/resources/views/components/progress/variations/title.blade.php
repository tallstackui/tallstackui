<div>
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $title }}</h3>
        <span class="text-sm text-gray-800 dark:text-white">{{ $percent }}%</span>
    </div>
    <div @class(['flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700', $personalize['sizes.' . $size]])
         role="progressbar"
         aria-valuenow="25"
         aria-valuemin="0"
         aria-valuemax="100">
        <div @class(['flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500', $colors['background']])
             style="width: {{ $percent }}%"></div>
    </div>
</div>
