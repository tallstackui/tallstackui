@php
    $customization = $classes();
@endphp

<div class="{{ $customization['wrapper'] }}">
    @if ($label)
        <x-dynamic-component :component="TallStackUi::prefix('label')"
                             scope="list.label"
                             :$label />
    @endif

    <div x-data="tallstackui_list()"
         class="{{ $customization['box'] }}">
        @if ($searchable)
            <div class="{{ $customization['search.wrapper'] }}">
                <span class="{{ $customization['search.icon.wrapper'] }}">
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('magnifying-glass')"
                                         internal
                                         class="{{ $customization['search.icon.size'] }}" />
                </span>
                <input type="search"
                       x-model.debounce.150ms="search"
                       class="{{ $customization['search.input'] }}"
                       placeholder="{{ $searchPlaceholder ?? __('ts-ui::messages.list.search') }}"
                       dusk="tallstackui_list_search" />
            </div>
        @endif

        <div @class([
                $customization['items.wrapper'],
                $customization['items.scroll'] => $height !== null,
                ($customization['items.height.'.$height] ?? '') => $height !== null,
             ])>
            @if ($items !== null)
                @foreach ($resolved as $item)
                    <x-dynamic-component :component="TallStackUi::prefix('list.items')"
                                         :name="data_get($item, 'name')"
                                         :caption="data_get($item, 'caption')">
                        @isset($item_menu)
                            <x-slot:menu>{{ $item_menu($item) }}</x-slot:menu>
                        @endisset
                    </x-dynamic-component>
                @endforeach
            @else
                {{ $slot }}
            @endif

            <div x-show="!hasResults"
                 x-cloak
                 class="{{ $customization['empty.wrapper'] }}"
                 dusk="tallstackui_list_empty">
                @if (isset($empty) && ! $empty->isEmpty())
                    {{ $empty }}
                @else
                    <p class="{{ $customization['empty.text'] }}">{{ __('ts-ui::messages.list.empty') }}</p>
                @endif
            </div>
        </div>
    </div>

    @if ($hint)
        <x-dynamic-component :component="TallStackUi::prefix('hint')"
                             scope="list.hint"
                             :$hint />
    @endif
</div>
