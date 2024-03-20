<div @class([$personalize['simple.wrapper'], $personalize['sizes.' . $size]])
     role="progressbar"
     aria-valuenow="{{ $percent }}"
     aria-valuemin="0"
     aria-valuemax="100">
    <div @class([$personalize['simple.progress'], $colors['background']])
         style="width: {{ $percent }}%">
          @if (!$withoutText)
               {{ $percent }}%
          @endif    
     </div>
</div>
