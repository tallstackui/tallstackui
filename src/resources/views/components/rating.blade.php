@php
    if (!$static) \TallStackUi\Foundation\Exceptions\MissingLivewireException::throwIf($livewire, 'reaction');
    $personalize = $classes();
    [$property] = $bind($attributes, livewire: $livewire);
    $rate ??= $livewire ? data_get($this, $property) : $rate;
@endphp

<div @class($personalize['wrapper'])
  x-data="{ rate: @js($rate), quantity: @js($quantity), evaluate(method, evaluate) {
        this.rate = evaluate;
        this.$el.dispatchEvent(new CustomEvent('evaluate', {detail: {evaluate: { method, rate: this.rate }}}));
        this.$wire.call(method, this.rate);
    }
 }">
    @if ($position === 'right')
        @if ($text)
        <p @class($personalize['text'])>
            {{ $text }}
        </p>
        @else
            {{ $slot }}
        @endif
    @endif
    <template x-for="(star, index) in Array.from({ length: quantity })" :key="index">
        <button @if (!$static) x-on:click.prevent="evaluate('{{ $evaluateMethod }}', index + 1);" @endif
                {{ $attributes->only('x-on:evaluate') }}
                @class($personalize['button'])>
                <svg xmlns="http://www.w3.org/2000/svg"
                     aria-hidden="true"
                     viewBox="0 0 24 24"
                     fill="currentColor"
                     @class($personalize['sizes.' . $size])
                     x-bind:class="{ '{{ $colors['background'] }}': rate >= index + 1, '{{ $personalize['star'] }}': rate < index + 1 }">
                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"></path>
                </svg>
        </button>
    </template>
    @if ($position === 'left')
        @if ($text)
            <p @class($personalize['text'])>
                {{ $text }}
            </p>
        @else
            {{ $slot }}
        @endif
    @endif
</div>
