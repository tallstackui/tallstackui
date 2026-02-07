@php
    $customization = $classes();
@endphp

<div x-cloak
     x-data="tallstackui_keyValue({!! $entangle !!}, @js($this->getId()), @js($limit), @js($static), @js($deleteMethod))"
     class="{{ $customization['wrapper'] }}">
    <div class="{{ $customization['header.wrapper'] }}">
        <p class="{{ $customization['header.key'] }}">{{ $label ?? trans('ts-ui::messages.key-value.headers.key') }}</p>
        <p class="{{ $customization['header.value'] }}">{{ $value ?? trans('ts-ui::messages.key-value.headers.value') }}</p>
        @if ($header)
            {{ $header }}
        @endif
    </div>
    <div x-bind:class="{ 'divide-y divide-gray-300 dark:divide-dark-500' : rows.length > 0 }">
        <div class="{{ $customization['empty.wrapper'] }}" dusk="tallstackui_empty_message" x-show="rows.length === 0">
            <p class="{{ $customization['empty.text'] }}">{{ trans('ts-ui::messages.key-value.empty') }}</p>
        </div>
        <template x-for="(row, index) in rows" :key="row.index ?? index">
            <div @class([
                    $customization['list.wrapper'],
                    'py-4' => ! $deletable,
                ])>
                <div>
                    <input x-model="row.key"
                           x-on:keyup.shift.enter="add"
                           x-on:keyup.enter="sync"
                           dusk="tallstackui_input_key"
                           @readonly($static)
                           @if ($placeholders) placeholder="{{ trans('ts-ui::messages.key-value.placeholders.key') }}"
                           @endif
                           class="{{ $customization['list.input.key'] }}" />
                </div>
                <div @class([
                        'relative pr-8 mr-2',
                        'top-2' => $deletable,
                    ])>
                    <div>
                        <input x-model="row.value"
                               x-on:keyup.shift.enter="add"
                               x-on:keyup.enter="sync"
                               dusk="tallstackui_input_value"
                               @if ($placeholders) placeholder="{{ trans('ts-ui::messages.key-value.placeholders.value') }}"
                               @endif
                               @readonly($static)
                               class="{{ $customization['list.input.value'] }}" />
                    </div>
                    @if ($deletable)
                        <button class="cursor-pointer"
                                type="button"
                                {{ $attributes->only('x-on:remove') }}
                                x-on:click="remove(index)">
                            @if ($icon instanceof \Illuminate\View\ComponentSlot)
                                {{ $icon }}
                            @else
                                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                     :icon="TallStackUi::icon($icon ?? 'trash')"
                                                     dusk="tallstackui_delete_row_button"
                                                     internal
                                                     class="{{ $customization['button.delete'] }}" />
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
            class="{{ $customization['button.add'] }}"
            x-show="addable">
        {{ trans('ts-ui::messages.key-value.add-row') }}
    </button>
</div>
