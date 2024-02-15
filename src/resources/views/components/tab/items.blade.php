@php
    $right = ($right) ? $right->toHtml() : null;
    $left = ($left) ? $left->toHtml() : null;
@endphp

<div x-show="selected === '{{ $tab }}'" role="tabpanel" x-init="tabs.push({ tab: '{{ $tab }}', right: @js($right), left: @js($left) });">
    {{ $slot }}
</div>