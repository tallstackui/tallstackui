@php
    $personalize = $classes();
@endphp

<div x-data="{ selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, tabs: [] }"
     @class($personalize['base.wrapper'])>
    <div @class($personalize['base.padding'])>
        <select x-model="selected" @class($personalize['base.select'])>
            <template x-for="item in tabs">
                <option x-bind:value="item.tab" x-text="item.tab"></option>
            </template>
        </select>
    </div>
    <ul @class($personalize['base.body'])>
        <template x-for="item in tabs">
            <li role="tab"
                x-on:click="selected = item.tab"
                x-bind:class="{
                    '{{ $personalize['item.select'] }}' : selected === item.tab,
                    '{{ $personalize['item.unselect'] }}' : selected !== item.tab
                }">
                <div @class($personalize['item.wrapper'])>
                    <template x-if="item.left">
                        <div x-html="item.left"></div>
                    </template>
                    <span x-text="item.tab"></span>
                    <template x-if="item.right">
                        <div x-html="item.right"></div>
                    </template>
                </div>
            </li>
        </template>
    </ul>
    <hr @class($personalize['base.divider'])>
    <div role="tablist" @class($personalize['base.content'])>
        {{ $slot }}
    </div>
</div>
