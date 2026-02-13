<div x-show="selected === @js($tab)" role="tabpanel" x-init="tabs.push({ tab: @js($tab), title: @js($title), right: @js($content['right']), left: @js($content['left']), href: @js($href), navigate: @js((bool) $navigate), navigateHover: @js((bool) $navigateHover) }); @if($shouldRender && $href) selected = @js($tab); @endif" aria-labelledby="{{ $tab }}">
    @if ($shouldRender)
        {{ $slot }}
    @endif
</div>
