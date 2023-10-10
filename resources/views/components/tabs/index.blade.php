@php
    $computed  = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property  = $computed[$directive];
    $customize = tallstackui_personalization('tabs', $customization())
@endphp

<div @if ($property)
         @if (!str($directive)->contains('.live'))
            x-data="tallstackui_tabs(@entangle($property))"
        @else
            x-data="tallstackui_tabs(@entangle($property).live)"
        @endif
     @else
         x-data="tallstackui_tabs(@js($selected))"
     @endif class="w-full" x-cloak
>
    <ul x-ref="tablist"
        role="tablist"
            @class($customize['wrapper'])>
        <template x-for="tabItem in tabHeadings">
            <li @class($customize['item.wrapper'])
                x-on:click="select(tabItem)"
                x-bind:aria-selected="selected(tabItem)"
                x-bind:class="selected(tabItem) ? '{{ $customize['item.selected']}}' : '{{ $customize['item.unselected']}}'"
                x-text="tabItem"
                role="tab">
            </li>
        </template>
    </ul>
    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
