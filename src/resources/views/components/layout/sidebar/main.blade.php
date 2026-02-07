@php
    $customization = $classes();
@endphp

<div class="{{ $customization['mobile.wrapper.first'] }}"
     x-show="tallStackUiMenuMobile">
    <div x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="{{ $customization['mobile.backdrop'] }}"
         x-show="tallStackUiMenuMobile"></div>
    <div class="{{ $customization['mobile.wrapper.second'] }}">
        <div x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="{{ $customization['mobile.wrapper.third'] }}"
             x-show="tallStackUiMenuMobile">
            @if (filled($customization['mobile.button.icon']))
                <div x-show="tallStackUiMenuMobile"
                     x-transition:enter="ease-in-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in-out duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="{{ $customization['mobile.button.wrapper'] }}">
                    <button x-on:click="tallStackUiMenuMobile = false" type="button" class="cursor-pointer">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon($customization['mobile.button.icon'])"
                                             internal
                                             class="{{ $customization['mobile.button.size'] }}" />
                    </button>
                </div>
            @endif
            <div @class([
                    $customization['mobile.wrapper.fourth'],
                    'soft-scrollbar' => $thinScroll,
                    'custom-scrollbar' => $thickScroll,
                 ])
                 x-on:click.outside="tallStackUiMenuMobile = false">
                @if ($brand)
                    {{ $brand }}
                @endif
                <div @class([$customization['mobile.wrapper.third'], $customization['mobile.wrapper.brand.margin'] => blank($brand)])>
                    <nav class="{{ $customization['mobile.wrapper.fifth'] }}">
                        <ul role="list" class="{{ $customization['mobile.wrapper.sixth'] }}">
                            {{ $slot }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="{{ $customization['desktop.wrapper.first.base'] }}"
     x-bind:class="{ '{{ $customization['desktop.wrapper.first.size'] }}' : $store['tsui.side-bar'].open }">
    <div @class([
            $customization['desktop.wrapper.second'],
            'soft-scrollbar' => $thinScroll,
            'custom-scrollbar' => $thickScroll,
        ]) @if ($collapsible) x-bind:class="{
            '{{ $customization['desktop.sizes.expanded'] }}' : $store['tsui.side-bar'].open,
            '{{ $customization['desktop.sizes.collapsed'] }}' : !$store['tsui.side-bar'].open,
        }" @endif x-cloak>
        @if ($collapsible)
            <div class="{{ $customization['desktop.collapse.wrapper'] }}">
                <button x-on:click="$store['tsui.side-bar'].toggle()" class="cursor-pointer">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($customization['desktop.collapse.buttons.expanded.icon'])"
                                         internal
                                         x-show="$store['tsui.side-bar'].open"
                                         class="{{ $customization['desktop.collapse.buttons.expanded.class'] }}" />
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($customization['desktop.collapse.buttons.collapsed.icon'])"
                                         internal
                                         x-show="!$store['tsui.side-bar'].open"
                                         class="{{ $customization['desktop.collapse.buttons.collapsed.class'] }}" />
                </button>
            </div>
        @endif
        @if ($brand)
            {{ $brand }}
        @endif
        <div @class([$customization['desktop.wrapper.third'], $customization['desktop.wrapper.brand.margin'] => blank($brand)])>
            <nav class="{{ $customization['desktop.wrapper.fourth'] }}">
                <ul role="list" class="{{ $customization['desktop.wrapper.fifth'] }}">
                    {{ $slot }}
                </ul>
            </nav>
        </div>
    </div>
</div>
