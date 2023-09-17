@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp

<div x-data="{
        show : false,
        model : @entangle($computed),
        selecteds : [],
        search : '',
        searchable : @js($searchable),
        dimensional : @js($selectable !== []),
        selectable : @js($selectable),
        multiple : @js($multiple !== null),
        placeholder : 'Selecione uma opção',
        init() {
            if (this.multiple && this.model) {
                this.selecteds = this.options.filter(option => this.model.includes(option[this.selectable.value]));
            } else if (this.dimensional) {
                const selected = this.options.find(option => option[this.selectable.value] === (this.model ?? this.model[0]));
                this.selecteds = [selected];
                this.placeholder = selected[this.selectable.label];
            } else {
                this.selecteds = this.options.find(option => option === this.model);
                this.placeholder = this.selecteds;
            }
        },
        select (option) {
            if (!this.multiple && this.selecteds && this.selected(option)) {
                this.clear();
                return;
            }

            if (this.multiple && this.selected(option)) {
                this.clear(option);
                return;
            }

            if (this.multiple && !this.selecteds) {
                this.selecteds = [];
            }

            this.selecteds = this.multiple
                ? [...this.selecteds, option]
                : [option];

            if (this.dimensional) {
                this.model = this.multiple
                    ? this.selecteds.map(selected => selected[this.selectable.value])
                    : option[this.selectable.value];
                this.placeholder = option[this.selectable.label];
            } else {
                this.model = this.multiple
                    ? this.selecteds
                    : option;
                this.placeholder = option;
            }

            this.show = this.multiple;
            this.search = '';
        },
        selected (option) {
            if (!this.selecteds) return false;

            if (!this.dimensional) {
                return this.selecteds === option;
            }

            if (this.multiple && this.selecteds.length >= 1) {
                return this.selecteds.some(selected => {
                    const keys   = Object.keys(selected);
                    const values = Object.values(selected);

                    return keys.every(key => {
                        return selected[key] === option[key];
                    }) && values.every(value => {
                        return selected[value] === option[value];
                    });
                });
            }

            const keys   = Object.keys(this.selecteds);
            const values = Object.values(this.selecteds);

            if (keys.length === 0 && values.length === 0) {
                return false;
            }

            return keys.every(key => {
                return this.selecteds[key] === option;
            }) && values.every(value => {
                return value === option;
            });
        },
        clear (selected = null) {
            if (this.multiple && selected) {
                this.selecteds = this.selecteds.filter(option => option !== selected);
                this.model = this.selecteds.map(selected => selected[this.selectable.value]);

                if (this.selecteds.length > 0) {
                    return;
                }

                this.clear();
            }

            this.model = null;
            this.placeholder = 'Selecione uma opção';
            this.selecteds = [];
            this.search = '';
            this.show = false;
        },
        get quantity() {
            return this.selecteds?.length;
        },
        get empty () {
            return this.selecteds === undefined || this.selecteds.length === 0;
        },
        get options () {
            const availables = @js($options);

            return this.search === ''
                ? availables
                : availables.filter(option => {
                    return this.dimensional
                        ? option[this.selectable.label].toLowerCase().includes(this.search.toLowerCase())
                        : option.toLowerCase().includes(this.search.toLowerCase())
                });
        }
    }" x-on:click.outside="show = false">
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div class="relative mt-2">
        <div @class([
                'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-gray-900',
                'shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                'text-red-600 ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500' => $error,
            ])
             role="combobox"
             aria-controls="options"
             aria-expanded="false">
            <div x-on:click="show = true" class="relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-2 transition space-x-2">
                <template x-if="multiple">
                    <div class="flex gap-2">
                        <template x-if="quantity === 0">
                            <span @class(['truncate font-medium', 'text-red-500' => $error]) x-bind:class="{ 'text-gray-400': selecteds }" x-text="placeholder"></span>
                        </template>
                        <!-- count -->
                        <template x-if="quantity > 0">
                            <span x-text="quantity"></span>
                        </template>
                        <!-- badges -->
                        <template wire:ignore x-for="(selected, index) in selecteds" :key="selected[selectable.label] ?? selected">
                            <a href="#" class="cursor-pointer" x-on:click="clear(selected);">
                                <div class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                    <span x-text="selected[selectable.label]"></span>
                                    <svg class="h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>
                <template x-if="!multiple">
                    <span @class(['truncate font-medium', 'text-red-500' => $error]) x-bind:class="{ 'text-gray-400': empty }" x-text="placeholder"></span>
                </template>
            </div>
            <div class="mr-1 flex items-center">
                <!-- clean up button -->
                <template x-if="Object.keys(selecteds ?? []).length > 0">
                    <button type="button" x-on:click="clear()">
                        <svg class="h-4 w-4 transition hover:text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </template>
            </div>
        </div>
        <!-- list -->
        <div class="relative">
            <ul wire:ignore x-show="show" class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" id="options" role="listbox">
                <template x-if="searchable">
                    <li class="m-2">
                        <!-- input --->
                        <input type="text"
                               name="account-number"
                               id="account-number"
                               class="block w-full rounded-md border-0 pr-10 placeholder:text-gray-400 text-gray-900 ring-1 ring-inset ring-gray-300 py-1.5 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                               placeholder="Procure alguma coisa"
                               x-model.debounce.500ms="search"
                        />
                    </li>
                </template>
                <template x-for="option in options" :key="option[selectable.label] ?? option">
                    <li x-on:click="select(option)"
                        class="relative cursor-pointer select-none py-2 pr-2 pl-3 text-gray-700 transition hover:bg-gray-100"
                        id="option-0"
                        role="option"
                        tabindex="-1"
                        x-bind:class="{ 'font-semibold hover:text-white hover:bg-red-500': selected(option) }"
                    >
                        <div wire:ignore class="flex items-center justify-between">
                            <span class="ml-2 truncate" x-text="option[selectable.label] ?? option"></span>
                            <svg x-show="selected(option)" class="h-5 w-5 font-bold text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                    </li>
                </template>
                <template x-if="options.length === 0">
                    <li class="m-2">
                    <span class="block w-full pr-2 text-gray-700">
                        Nenhum resultado encontrado
                    </span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
    @if ($hint && !$error)
        <span class="mt-2 text-sm text-secondary-500">
        {{ $hint }}
    </span>
    @endif
    @error ($computed)
    <span class="mt-2 text-sm text-red-500">
        {{ $message }}
    </span>
    @enderror
</div>
