@php
    $right = is_string($right) ? $right : ($right?->toHtml() ?? null);
    $left = is_string($left) ? $left : ($left?->toHtml() ?? null);
@endphp

<div x-show="selected === @js($tab)" role="tabpanel" x-init="tabs.push({ tab: @js($tab), right: @js($right), left: @js($left) });">
    {{ $slot }}
</div>
