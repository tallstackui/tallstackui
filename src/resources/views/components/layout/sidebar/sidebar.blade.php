@php
    $personalize = $classes();
@endphp

<div
    class="{{ $personalize["mobile.wrapper.first"] }}"
    x-show="tallStackUiMenuMobile"
>
    <div
        x-transition:enter="transition-opacity duration-300 ease-linear"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300 ease-linear"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="{{ $personalize["mobile.backdrop"] }}"
        x-show="tallStackUiMenuMobile"
    ></div>
    <div class="{{ $personalize["mobile.wrapper.second"] }}">
        <div
            x-transition:enter="transform transition duration-300 ease-in-out"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-300 ease-in-out"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="{{ $personalize["mobile.wrapper.third"] }}"
            x-show="tallStackUiMenuMobile"
        >
            @if (filled($personalize["mobile.button.icon"]))
                <div
                    x-show="tallStackUiMenuMobile"
                    x-transition:enter="duration-300 ease-in-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="duration-300 ease-in-out"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="{{ $personalize["mobile.button.wrapper"] }}"
                >
                    <button
                        x-on:click="tallStackUiMenuMobile = false"
                        type="button"
                        class="cursor-pointer"
                    >
                        <x-dynamic-component
                            :component="TallStackUi::prefix('icon')"
                            :icon="TallStackUi::icon($personalize['mobile.button.icon'])"
                            internal
                            class="{{ $personalize['mobile.button.size'] }}"
                        />
                    </button>
                </div>
            @endif

            <div
                @class([
                    $personalize["mobile.wrapper.fourth"],
                    "soft-scrollbar" => $thinScroll,
                    "custom-scrollbar" => $thickScroll,
                ])
                x-on:click.outside="tallStackUiMenuMobile = false"
            >
                @if ($brand)
                    {{ $brand }}
                @endif

                <div
                    @class([$personalize["mobile.wrapper.third"], $personalize["mobile.wrapper.brand.margin"] => blank($brand)])
                >
                    <nav class="{{ $personalize["mobile.wrapper.sixth"] }}">
                        <ul
                            role="list"
                            class="{{ $personalize["mobile.wrapper.seventh"] }}"
                        >
                            {{ $slot }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="{{ $personalize["desktop.wrapper.first"] }}">
    <div
        @class([
            $personalize["desktop.wrapper.second"],
            "soft-scrollbar" => $thinScroll,
            "custom-scrollbar" => $thickScroll,
        ])
    >
        @if ($brand)
            {{ $brand }}
        @endif

        <div
            @class([$personalize["desktop.wrapper.third"], $personalize["desktop.wrapper.brand.margin"] => blank($brand)])
        >
            <nav class="{{ $personalize["desktop.wrapper.fourth"] }}">
                <ul
                    role="list"
                    class="{{ $personalize["desktop.wrapper.fifth"] }}"
                >
                    {{ $slot }}
                </ul>
            </nav>
        </div>
    </div>
</div>
