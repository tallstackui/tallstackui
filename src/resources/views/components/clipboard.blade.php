@php
    $text ??= $slot->toHtml();
    $hash = md5($text.uniqid());
@endphp

<div x-data="tallstackui_clipboard(@js($text), @js($hash), @js($type), @js($placeholders['button']))">
    @if ($label)
        <x-label :$label/>
    @endif
    <div @class(['mt-1', 'flex' => $type === 'input'])>
        @if ($type === 'input')
            @if ($left)
                <button data-hash="{{ $hash }}"
                        x-on:click="copy()"
                        class="'relative -mr-px inline-flex items-center gap-x-1.5 rounded-l-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50'"
                        type="button">
                    <x-icon name="clipboard-document" class="h-5 w-5 text-primary-500 cursor-pointer" />
                    <p x-ref="input-{{ $hash }}">{{ $placeholders['button']['copy'] }}</p>
                </button>
            @endif
            <div class="relative flex flex-grow items-stretch focus-within:z-10">
                <input type="text"
                       @class([
                            'block w-full rounded-none border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                            'rounded-l-md' => ! $left,
                            'rounded-r-md' => $left,
                       ]) value="{{ $text }}" readonly>
            </div>
            @if (! $left)
                <button data-hash="{{ $hash }}"
                        x-on:click="copy()"
                        type="button"
                        class="relative -ml-px inline-flex items-center rounded-r-md gap-x-1.5 px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <x-icon name="clipboard-document" class="h-5 w-5 text-primary-500 cursor-pointer" />
                    <p x-ref="input-{{ $hash }}">{{ $placeholders['button']['copy'] }}</p>
                </button>
            @endif
        @endif
        @if ($type === 'textarea')
            <div class="relative min-w-0 flex-1">
                <div class="overflow-hidden rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-indigo-600">
                    <textarea rows="3" name="comment" id="comment" class="block w-full resize-none border-0 bg-transparent py-1.5 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">{{ $text }}</textarea>
                    <div class="py-2" aria-hidden="true">
                        <div class="py-px">
                            <div class="h-9"></div>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 flex justify-end py-2 pl-3 pr-2">
                    <div class="inline-flex">
                        <x-button data-hash="{{ $hash }}" x-on:click.prevent="copy()" sm>
                            <x-icon name="clipboard-document"  class="h-5 w-5 text-white cursor-pointer" />
                            <p x-ref="textarea-{{ $hash }}">{{ $placeholders['button']['copy'] }}</p>
                        </x-button>
                    </div>
                </div>
            </div>
        @endif
        @if ($type === 'icon')
            <div class="inline-flex">
                <x-icon name="clipboard"
                        class="h-5 w-5 text-primary-500 cursor-pointer"
                        data-hash="{{ $hash }}"
                        x-on:click="copy()" x-show="!notification" />
                <x-icon name="document-check"
                        class="h-5 w-5 cursor-none"
                        x-bind:class="{ 'text-green-500': notification, 'text-red-500': !notification }"
                        x-show="notification" />
            </div>
        @endif
    </div>
</div>
