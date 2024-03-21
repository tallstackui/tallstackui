@php($personalize = $classes())

<div>
    <x-dynamic-component component="tallstack-ui::progress.variations.{{ $variation }}"
                         :$title
                         :$percent
                         :$size
                         :$colors
                         :$withoutText
                         :$personalize />
    @if ($footer)
       <div>{{ $footer }}</div>
    @endif
</div>
