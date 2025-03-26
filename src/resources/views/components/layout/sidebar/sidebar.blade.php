@php
    $personalize = $classes();
@endphp

<div class="{{ $personalize['mobile.wrapper.first'] }}" x-show="tallStackUiMenuMobile">
    <div x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="{{ $personalize['mobile.backdrop'] }}"
         x-show="tallStackUiMenuMobile"></div>
    <div class="{{ $personalize['mobile.wrapper.second'] }}">
        <div x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="{{ $personalize['mobile.wrapper.third'] }}"
             x-show="tallStackUiMenuMobile">
            @if (filled($personalize['mobile.button.icon']))
            <div x-show="tallStackUiMenuMobile"
                 x-transition:enter="ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="{{ $personalize['mobile.button.wrapper'] }}">
                <button x-on:click="tallStackUiMenuMobile = false" type="button">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon($personalize['mobile.button.icon'])"
                                         internal
                                         class="{{ $personalize['mobile.button.size'] }}" />
                </button>
            </div>
            @endif
            <div @class([
                    $personalize['mobile.wrapper.fourth'],
                    'soft-scrollbar' => $thinScroll,
                    'custom-scrollbar' => $thickScroll,
                 ])
                 x-on:click.outside="tallStackUiMenuMobile = false">
                @if ($brand)
                    {{ $brand }}
                @endif
                <div class="{{ $personalize['mobile.wrapper.fifth'] }}">
                    <nav class="{{ $personalize['mobile.wrapper.sixth'] }}">
                        <ul role="list" class="{{ $personalize['mobile.wrapper.seventh'] }}">
                            {{ $slot }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="{{ $personalize['desktop.wrapper.first'] }}">
    <div @class([
            $personalize['desktop.wrapper.second'],
            'soft-scrollbar' => $thinScroll,
            'custom-scrollbar' => $thickScroll,
        ])>
        @if ($brand)
            {{ $brand }}
        @endif
        <div class="{{ $personalize['desktop.wrapper.third'] }}">
            <nav class="{{ $personalize['desktop.wrapper.fourth'] }}">
                <ul role="list" class="{{ $personalize['desktop.wrapper.fifth'] }}">
                    {{ $slot }}
                </ul>
            </nav>
        </div>
    </div>
</div>
