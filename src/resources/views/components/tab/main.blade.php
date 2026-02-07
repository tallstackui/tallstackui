@php
    $customization = $classes();
@endphp

<div x-data="{ selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, tabs: [] }"
     class="{{ $customization['base.wrapper'] }}">
    @if (!$scrollOnMobile)
        <div class="{{ $customization['base.padding'] }}">
            <select x-model="selected" class="{{ $customization['base.select'] }}" aria-label="Select a tab"
                    x-on:change="$refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: selected}}));">
                <template x-for="item in tabs">
                    <option x-bind:value="item.tab" x-text="item.title ?? item.tab"
                            x-bind:selected="item.tab === selected">
                    </option>
                </template>
            </select>
        </div>
    @endif
    <ul role="tablist"
        @class([$customization['base.body'], 'hidden sm:flex' => ! $scrollOnMobile, 'justify-center' => $centered]) {{ $attributes->only('x-on:navigate') }} x-ref="ul">
        <template x-for="item in tabs">
            <li role="tab"
                tabindex="0"
                x-on:click="selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}}));"
                x-on:keypress.enter="selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}}));"
                x-bind:aria-selected="selected === item.tab ? 'true' : 'false'"
                x-bind:class="{
                    '{{ $customization['item.select'] }}' : selected === item.tab,
                    '{{ $customization['item.unselect'] }}' : selected !== item.tab,
                    'hidden sm:flex': selected !== item.tab && ! @js($scrollOnMobile),
                }">
                <div class="{{ $customization['item.wrapper'] }}">
                    <template x-if="item.left">
                        <div x-html="item.left"></div>
                    </template>
                    <span x-text="item.title ?? item.tab"></span>
                    <template x-if="item.right">
                        <div x-html="item.right"></div>
                    </template>
                </div>
            </li>
        </template>
    </ul>
    <hr @class([$customization['base.divider'], 'hidden sm:block' => ! $scrollOnMobile])>
    <div class="{{ $customization['base.content'] }}">
        {{ $slot }}
    </div>
</div>
