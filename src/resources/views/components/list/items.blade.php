@php
    $customization = $classes();
    $hasMenu = $menu instanceof \Illuminate\View\ComponentSlot
        ? ! $menu->isEmpty()
        : ! empty($menu);
@endphp

<div class="{{ $customization['wrapper'] }}"
     data-list-row
     data-list-name="{{ $name }}"
     data-list-caption="{{ $caption }}"
     x-show="match(@js($name), @js($caption ?? ''))"
     x-init="register(@js($name), @js($caption ?? ''))">
    <div class="{{ $customization['content.wrapper'] }}">
        <div class="{{ $customization['content.inner'] }}">
            <span class="{{ $customization['name'] }}">{{ $name }}</span>
            @if ($caption !== null && $caption !== '')
                <span class="{{ $customization['caption'] }}">{{ $caption }}</span>
            @endif
            @if (isset($slot) && ! $slot->isEmpty())
                {{ $slot }}
            @endif
        </div>
        @if ($hasMenu)
            <div class="{{ $customization['menu.wrapper'] }}">
                <x-dynamic-component :component="TallStackUi::prefix('dropdown')"
                                     scope="list.items.menu"
                                     position="bottom-end">
                    <x-slot:action>
                        <button type="button"
                                class="{{ $customization['menu.trigger'] }}"
                                x-on:click="show = !show; $refs.dropdown.dispatchEvent(new CustomEvent('open', {detail: {status: show}}))"
                                dusk="tallstackui_list_items_menu">
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon('ellipsis-vertical')"
                                                 internal
                                                 class="{{ $customization['menu.icon'] }}" />
                        </button>
                    </x-slot:action>
                    {{ $menu }}
                </x-dynamic-component>
            </div>
        @endif
    </div>
</div>
