@php($personalize = $classes())

<div x-data="tallstackui_step(@if (!$selected) {!! TallStackUi::blade($attributes, $livewire)->entangle() !!} @else @js($selected) @endif)"
     {{ $attributes->only('x-on:change') }}
     x-cloak>
    <nav aria-label="Form Step">
        <ul role="list" @class($personalize['wrapper.' . $variation])>
             <template x-for="item in steps">
                @include("tallstack-ui::components.step.variation.$variation")
            </template>
        </ul>
    </nav>

    <div role="tablist" @class($personalize['content'])>
        {{ $slot }}
    </div>

    @if ($helpers)
        <div class="flex justify-between">
            <div>
                <x-button x-show="selected > 1" x-on:click="selected--;">Anterior</x-button>
            </div>
            <div>
                <x-button x-show="selected < steps.length" x-on:click="selected++;">Próximo</x-button>
                @if ($finish)
                    <x-button x-show="selected == steps.length" x-on:click="finish()" {{ $attributes->only('x-on:finish') }}>Finalizar</x-button>
                @endif
            </div>
        </div>
    @endif
</div>
