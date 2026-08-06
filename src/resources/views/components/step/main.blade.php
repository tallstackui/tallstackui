@php
    $customization = $classes();
@endphp

<div x-data="{
        selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif,
        navigate: @js($navigate),
        navigatePrevious: @js($navigatePrevious),
        steps: [],
        next() {
            window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));
            this.selected++;
            this.$refs.buttons?.dispatchEvent(new CustomEvent('change', { detail: { step: this.selected } }));
        },
        previous() {
            window.dispatchEvent(new CustomEvent('tallstackui:floating-flush'));
            this.selected--;
            this.$refs.buttons?.dispatchEvent(new CustomEvent('change', { detail: { step: this.selected } }));
        },
    }">
    <nav @if ($variation === 'panels') class="overflow-hidden {{ $customization['panels-shape'] }}" @endif>
        <ul role="list"
                @class($customization['wrapper.' . $variation])>
            <template x-for="item in steps">
                <x-dynamic-component component="ts-ui::step.variations.{{ $variation }}"
                                     :$customization
                                     :$navigate />
            </template>
        </ul>
    </nav>
    <div class="{{ $customization['content'] }}">
        {{ $slot }}
    </div>
    @if ($helpers)
        @include($helper())
    @endif
</div>
