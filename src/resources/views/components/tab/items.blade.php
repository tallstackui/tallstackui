<div x-show="selected === @js($tab)" role="tabpanel" x-init="tabs.push({ tab: @js($tab), title: @js($title), right: @js($content['right']), left: @js($content['left']) });" aria-labelledby="{{ $tab }}">
    {{ $slot }}
</div>
