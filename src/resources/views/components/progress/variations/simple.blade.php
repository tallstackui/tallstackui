<div @class(['flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700', $personalize['sizes.' . $size]])
     role="progressbar"
     aria-valuenow="{{ $percent }}"
     aria-valuemin="0"
     aria-valuemax="100">
    <div @class(['flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500', $colors['background']])
         style="width: {{ $percent }}%">
          @if($withText)
               {{ $percent }}%
          @endif    
     </div>
</div>
