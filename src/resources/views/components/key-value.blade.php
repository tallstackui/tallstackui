<div x-data="tallstackui_keyValue({!! $entangle !!}, @js($this->getId()), @js($limit), @js($static), @js($deleteMethod))"
     class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden text-sm dark:bg-dark-600 dark:border-dark-600">
    <div class="grid grid-cols-2 bg-gray-200 px-4 py-2 text-gray-600 dark:text-dark-300 dark:bg-dark-700">
        <p class="font-semibold">{{ $label ?? trans('tallstack-ui::messages.key-value.headers.key') }}</p>
        <p class="font-semibold">{{ $value ?? trans('tallstack-ui::messages.key-value.headers.value') }}</p>
        @if ($header)
            {{ $header }}
        @endif
    </div>
    <div x-bind:class="{ 'divide-y divide-gray-300 dark:divide-dark-500' : rows.length > 0 }">
        <div class="flex items-center justify-center py-5" dusk="tallstackui_empty_message" x-show="rows.length === 0">
            <p class="text-gray-500 dark:text-dark-300">{{ trans('tallstack-ui::messages.key-value.empty') }}</p>
        </div>
        <template x-for="(row, index) in rows" :key="row.index ?? index">
            <div @class([
                    'grid grid-cols-2 px-4 items-center relative',
                    'py-4' => ! $deletable,
                ])>
                <div class="text-gray-600">
                    <input x-model="row.key"
                           x-on:keyup.shift.enter="add"
                           x-on:keyup.enter="sync"
                           dusk="tallstackui_input_key"
                           @if ($placeholders) placeholder="{{ trans('tallstack-ui::messages.key-value.placeholders.key') }}"
                           @endif
                           class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full dark:bg-dark-600 dark:text-white dark:placeholder:text-dark-400"/>
                </div>
                <div @class([
                        'relative pr-8 mr-2',
                        'top-2' => $deletable,
                    ])>
                    <div class="text-gray-600">
                        <input x-model="row.value"
                               x-on:keyup.shift.enter="add"
                               x-on:keyup.enter="sync"
                               dusk="tallstackui_input_value"
                               @if ($placeholders) placeholder="{{ trans('tallstack-ui::messages.key-value.placeholders.value') }}"
                               @endif
                               class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full dark:bg-dark-600 dark:text-white dark:placeholder:text-dark-400"/>
                    </div>
                    @if ($deletable)
                        <button class="cursor-pointer"
                                {{ $attributes->only('x-on:remove') }}
                                x-on:click="remove(index)">
                            @if ($icon instanceof \Illuminate\View\ComponentSlot)
                                {{ $icon }}
                            @else
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon($icon ?? 'trash')"
                                                     dusk="tallstackui_delete_row_button"
                                                     internal
                                                     class="absolute top-2 right-0 h-5 w-5 text-red-500 "/>
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </template>
    </div>
    <button x-on:click="add"
            type="button"
            dusk="tallstackui_add_row_button"
            {{ $attributes->only('x-on:add') }}
            class="px-4 py-2 text-center text-gray-600 hover:underline cursor-pointer bg-gray-200 dark:bg-dark-700 dark:text-dark-300"
            x-show="addable === true">
        {{ trans('tallstack-ui::messages.key-value.add-row') }}
    </button>
</div>
