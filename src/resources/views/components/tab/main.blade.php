@php
    $customization = $classes();
@endphp

<div x-data="{ selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif, tabs: [] }"
     class="{{ $customization['base.wrapper'] }}">
    @if (!$scrollOnMobile)
        <div class="{{ $customization['base.padding'] }}">
            <select x-model="selected" class="{{ $customization['base.select'] }}" aria-label="Select a tab"
                    x-on:change="let t = tabs.find(i => i.tab === selected); if (t && t.when) { if (t.navigate || t.navigateHover) { Livewire.navigate(t.when); } else { window.location.href = t.when; } } else { $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: selected}})); }">
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
                x-on:click="if (item.when) { if (item.navigate || item.navigateHover) { Livewire.navigate(item.when); } else { window.location.href = item.when; } } else { selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}})); }"
                x-on:keypress.enter="if (item.when) { if (item.navigate || item.navigateHover) { Livewire.navigate(item.when); } else { window.location.href = item.when; } } else { selected = item.tab; $refs.ul.dispatchEvent(new CustomEvent('navigate', {detail: {select: item.tab}})); }"
                x-on:mouseenter="if (item.when && item.navigateHover && !item._prefetched) { let link = document.createElement('link'); link.rel = 'prefetch'; link.href = item.when; document.head.appendChild(link); item._prefetched = true; }"
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
