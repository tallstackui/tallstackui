@php
    $computed = $attributes->whereStartsWith('wire:model');
    $directive = array_key_first($computed->getAttributes());
    $property = $computed[$directive];
    $customize = tallstackui_personalization('tabs', $personalization());
@endphp

<div @if ($property)
         @if (!str($directive)->contains('.live'))
             x-data="tallstackui_tabs(@entangle($property))"
         @else
             x-data="tallstackui_tabs(@entangle($property).live)"
         @endif
     @else
         x-data="tallstackui_tabs(@js($selected))"
     @endif class="w-full" x-cloak>
    <ul x-ref="tablist" role="tablist" @class($customize['wrapper'])>
        <template x-for="item in headings">
            <li @class([$customize['item.wrapper'], 'rounded-t-lg' => !$square])
                x-on:click="select(item)"
                x-bind:aria-selected="selected(item)"
                x-bind:class="selected(item) ? '{{ $customize['item.selected']}}' : '{{ $customize['item.unselected']}}'"
                x-text="item"
                role="tab">
            </li>
        </template>
    </ul>
    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
