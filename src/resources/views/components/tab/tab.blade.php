@php
    $personalize = $classes();
@endphp

<div x-data="{ selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, tabs: [] }" class="{{ $personalize['base.wrapper'] }}">
    @if (!$scrollOnMobile)
        <div class="{{ $personalize['base.padding'] }}">
            <select x-model="selected" class="{{ $personalize['base.select'] }}" aria-label="Select a tab" x-on:change="$refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: selected}}));">
                <template x-for="item in tabs">
                    <option x-bind:value="item.tab" x-text="item.tab" x-bind:selected="item.tab === selected"></option>
                </template>
            </select>
        </div>
    @endif
    <ul role="tablist" @class([$personalize['base.body'], 'hidden sm:flex' => ! $scrollOnMobile]) {{ $attributes->only('x-on:navigate') }} x-ref="ul">
        <template x-for="item in tabs">
            <li role="tab"
                tabindex="0"
                x-on:click="selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}}));"
                x-on:keypress.enter="selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}}));"
                x-bind:aria-selected="selected === item.tab ? 'true' : 'false'"
                x-bind:class="{
                    '{{ $personalize['item.select'] }}' : selected === item.tab,
                    '{{ $personalize['item.unselect'] }}' : selected !== item.tab,
                    'hidden sm:flex': selected !== item.tab && ! @js($scrollOnMobile),
                }">
                <div class="{{ $personalize['item.wrapper'] }}">
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
    <hr @class([$personalize['base.divider'], 'hidden sm:block' => ! $scrollOnMobile])>
    <div class="{{ $personalize['base.content'] }}">
        {{ $slot }}
    </div>
</div>
