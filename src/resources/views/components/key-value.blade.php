<div x-data="data(@js($limit), @js($static))" class="bg-gray-100 border border-gray-200 rounded-lg overflow-hidden text-sm">
    <div class="grid grid-cols-2 bg-gray-200 px-4 py-2 text-gray-600">
        <p class="font-semibold">{{ $label ?? trans('tallstack-ui::messages.key-value.headers.key') }}</p>
        <p class="font-semibold">{{ $value ?? trans('tallstack-ui::messages.key-value.headers.value') }}</p>
        @if ($header)
            {{ $header }}
        @endif
    </div>
    <div x-bind:class="{ 'divide-y divide-gray-300' : rows.length > 0 }">
        <div class="flex items-center justify-center py-5" x-show="rows.length === 0">
            <p class="text-gray-500">{{ trans('tallstack-ui::messages.key-value.empty') }}</p>
        </div>
        <template x-for="(row, index) in rows" :key="row.index ?? index">
            <div @class([
                    'grid grid-cols-2 px-4 items-center relative',
                    'py-4' => ! $removable,
                ])>
                <div class="text-gray-600">
                    <input x-model="row.key"
                           @if ($placeholders) placeholder="{{ trans('tallstack-ui::messages.key-value.placeholders.key') }}" @endif
                           class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
                </div>
                <div @class([
                        'relative pr-8 mr-2',
                        'top-2' => $removable,
                    ])>
                    <div class="text-gray-600">
                        <input x-model="row.value"
                               @if ($placeholders) placeholder="{{ trans('tallstack-ui::messages.key-value.placeholders.value') }}" @endif
                               class="background-transparent border-0 bg-gray-100 focus:ring-0 focus:outline-none w-full" />
                    </div>
                    @if ($removable)
                        <button class="cursor-pointer"
                                {{ $attributes->only('x-on:remove') }}
                                x-on:click="remove(index)">
                            @if ($icon instanceof \Illuminate\View\ComponentSlot)
                                {{ $icon }}
                            @else
                            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                                 :icon="TallStackUi::icon($icon ?? 'trash')"
                                                 internal
                                                 class="absolute top-2 right-0 h-5 w-5 text-red-500 hover:text-red-700" />
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </template>
    </div>
    <div x-on:click="add"
         {{ $attributes->only('x-on:add') }}
         class="px-4 py-2 text-center text-gray-600 hover:underline cursor-pointer bg-gray-200"
         x-show="addable === true">
        {{ trans('tallstack-ui::messages.key-value.add-row') }}
    </div>
</div>

<script>
    function data (limit, addable) {
        return {
            model: null,
            rows: [],
            init() {
                // alert(1);
            },
            add() {
                if (limit && this.rows.length >= limit) {
                    return
                }

                this.rows.push({
                    index: Math.random().toString(36).substring(2, 12),
                    key: '',
                    value: '',
                })

                this.$el.dispatchEvent(new CustomEvent('add', {
                    detail: {
                        rows: this.rows,
                    },
                }))
            },
            remove(index) {
                this.rows = this.rows.filter((_, i) => i !== index)

                this.$el.dispatchEvent(new CustomEvent('remove', {
                    detail: {
                        rows: this.rows,
                    },
                }))
            },
            get addable () {
                const value = Number(limit);

                return limit && this.rows.length < value || addable === false
            }
        }
    }
</script>
