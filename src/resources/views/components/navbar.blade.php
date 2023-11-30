@php
    /** @var \Illuminate\View\ComponentSlot|string $left */
    $text ??= $slot->toHtml();
@endphp

@if ($show)
    <div x-data="{
             show : @js($animated === false && $wire === false),
             animated : @js($animated),
             type : 'primary',
             text : @js($text),
             init() {
                if (this.animated) {
                    setTimeout(() => this.show = true, @js($seconds * 1000));
                }
            },
            manage(event) {
                this.show = true;
                this.type = event.detail.type;
                this.text = event.detail.text;
                this.animated = event.detail.animated;

                if (event.detail.timeout) {
                    setTimeout(() => this.show = false, event.detail.timeout * 1000);
                }
            }
         }"
         @class([
            'relative flex flex-row items-center justify-between px-6 py-2',
            'bg-primary-600' => $color === 'primary' && !$wire,
         ])
         @if ($wire)
         x-bind:class="{
            'bg-green-600' : type === 'success',
            'bg-yellow-600' : type === 'warning',
            'bg-blue-600' : type === 'info',
            'bg-red-600' : type === 'danger'
         }" @endif
         x-show="show"
         x-cloak
         @if ($wire) x-on:tallstackui:navbar.window="manage($event)" @endif
         @if ($animated || $close || $wire)
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-y-10"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="-translate-y-10"
        @endif>
        @if ($left)
            <span @if (!is_string($left)) {{ $left->attributes->merge(['class' => 'absolute left-0 ml-4 text-sm font-medium text-primary-50']) }} @endif>
                {!! $left !!}
            </span>
        @endif
        <span class="flex-grow text-center text-sm font-medium text-primary-50" x-text="text"></span>
        @if ($close)
            <x-icon name="x-mark"
                    class="h-4 w-4 cursor-pointer text-primary-50"
                    x-on:click="show = false"
            />
        @endif
    </div>
@endif
