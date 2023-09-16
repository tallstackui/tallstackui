@php
    $computed = $attributes->whereStartsWith('wire:model')->first();
    $error    = $errors->has($computed);
@endphp


<div x-data="{
        show : false,
        model : @entangle($computed).live,
        select (option) {
            this.model = option;
            this.show = false;
        }
    }">
    @if ($label)
        <x-label :$label :$error />
    @endif
    <div class="relative mt-2">
        <div class="flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white pr-12 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 py-1.5 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
             role="combobox"
             aria-controls="options"
             aria-expanded="false"
             x-on:click="show = !show"
        >
            <div class="relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-3 transition space-x-2">
                <span class="text-gray-700" x-text="model"></span>
            </div>
            <div>
                <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
        <button x-on:click="show = !show" type="button" class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <ul x-show="show" class="z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" id="options" role="listbox">
            @forelse ($options as $option)
                @php
                    $label = $select ? $option[$selectable['label']] : $option;
                    $value = $select ? $option[$selectable['value']] : $option;
                @endphp
                <li x-on:click="select('@js($value)')" class="relative cursor-pointer select-none py-2 pr-9 pl-3 text-gray-900 hover:bg-gray-100 transition" id="option-0" role="option" tabindex="-1">
                    <div class="flex items-center">
                        <span class="ml-2 truncate">
                          {{ $label }}
                        </span>
                    </div>
                </li>
            @empty
                Nenhuma opção encontrada.
            @endforelse
        </ul>
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
