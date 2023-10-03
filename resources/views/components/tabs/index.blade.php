@php($customize = tasteui_personalization('tabs', $customization()))

<div x-data="tasteui_tabs(@js($selected))" class="w-full" x-cloak>
    <ul x-ref="tablist"
        role="tablist"
        @class($customize['wrapper'])>
        @foreach ($options as $tab)
            <li id="{{ $tab }}"
                @class($customize['item.wrapper'])
                x-on:click="select(@js($tab))"
                x-bind:aria-selected="selected(@js($tab))"
                x-bind:class="selected(@js($tab)) ? '{{ $customize['item.selected']}}' : '{{ $customize['item.unselected']}}'"
                role="tab">
                {{ $tab }}
            </li>
        @endforeach
    </ul>
    <div>
        {{ $slot }}
    </div>
</div>
