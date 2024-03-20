@php($personalize = $classes())

<x-dynamic-component component="tallstack-ui::progress.variations.{{ $variation }}"
                     :$title
                     :$percent
                     :$size
                     :$colors
                     :$withoutText
                     :$personalize />
