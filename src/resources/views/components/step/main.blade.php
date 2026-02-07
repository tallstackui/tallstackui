@php
    $customization = $classes();
@endphp

<div x-data="{
        selected: @if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif,
        navigate: @js($navigate),
        previous: @js($navigatePrevious),
        steps: [],
    }">
    <nav @if ($variation === 'panels') class="overflow-hidden rounded-md" @endif>
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
        <div class="{{ $customization['helpers.wrapper'] }}" {{ $attributes->only('x-on:change') }} x-ref="buttons">
            <div>
                @if ($navigatePrevious)
                    <button type="button"
                            x-show="selected > 1"
                            x-on:click="selected--; $refs.buttons.dispatchEvent(new CustomEvent('change', {detail: {step: selected}}));"
                            dusk="tallstackui_step_previous"
                            class="{{ $customization['button.base'] }}">
                        <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                             :icon="TallStackUi::icon('chevron-left')"
                                             internal
                                @class(['mr-1', $customization['button.icon']]) />
                        {{ trans('ts-ui::messages.step.previous') }}
                    </button>
                @endif
            </div>
            <div>
                <button type="button"
                        x-show="selected < steps.length"
                        x-on:click="selected++; $refs.buttons.dispatchEvent(new CustomEvent('change', {detail: {step: selected}}));"
                        dusk="tallstackui_step_next"
                        class="{{ $customization['button.base'] }}">
                    {{ trans('ts-ui::messages.step.next') }}
                    <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                         :icon="TallStackUi::icon('chevron-right')"
                                         internal
                            @class(['ml-1', $customization['button.icon']]) />
                </button>
                @if ($finish)
                    @if ($finish instanceof \Illuminate\View\ComponentSlot)
                        <div x-show="selected === steps.length">
                            {{ $finish }}
                        </div>
                    @else
                        <button type="button"
                                x-show="selected === steps.length"
                                x-on:click="$el.dispatchEvent(new CustomEvent('finish', {detail: {step: selected}}))"
                                dusk="tallstackui_step_finish"
                                {{ $attributes->only('x-on:finish') }}
                                class="{{ $customization['button.base'] }}">
                            {{ trans('ts-ui::messages.step.finish') }}
                        </button>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
