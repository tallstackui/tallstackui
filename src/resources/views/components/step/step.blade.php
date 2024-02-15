@php($personalize = $classes())

<div x-data="tallstackui_step()"
     {{ $attributes->only('x-on:change') }}
     x-cloak>
    <nav aria-label="Progress Step">
        <ol role="list" @class($personalize['wrapper.' . $variation])>
            {{ $slot }}
        </ol>
    </nav>

    <div id="step-content-{{ $id }}" @class($personalize['content']) x-ref="content"></div>

    @if ($helpers)
        <div class="flex justify-between">
            <div>
                <x-button x-show="currentStep > 1" x-on:click="currentStep--;">Anterior</x-button>
            </div>
            <div>
                <x-button x-show="currentStep < total" x-on:click="currentStep++;">Próximo</x-button>
                @if ($finish)
                    <x-button x-show="currentStep == total" x-on:click="finish()" {{ $attributes->only('x-on:finish') }}>Finalizar</x-button>
                @endif
            </div>
        </div>
    @endif
</div>
