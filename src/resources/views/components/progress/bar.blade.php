@php
    $personalize = $classes();
@endphp

<div>
    <x-dynamic-component component="ts-ui::progress.variations.{{ $variation }}"
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
