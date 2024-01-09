<div class="flex" x-on:click="$refs.input.focus()" x-data="{tags: ['what', 'is', 'up', 'foo', 'bar', 'baz', 'bah', 'x1', 'test'], newTag: '', inputName: 'foo' }">
    <template x-for="tag in tags">
        <input type="hidden" x-bind:name="inputName + '[]'" x-bind:value="tag">
    </template>

    <div class="relative flex w-full rounded-lg px-2 placeholder:text-gray-400 text-gray-600 ring-1 ring-gray-300 transition focus-within:ring-primary-600 focus-within:ring-2 focus:ring-primary-600 dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 focus-within:focus:ring-primary-600 dark:focus-within:ring-primary-600">
        <div class="flex flex-wrap gap-x-2 border-0 bg-white pr-4 pb-1.5 dark:bg-dark-800">
            <template x-for="tag in tags" :key="tag">
                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-200 mt-1.5 py space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600">
                    <span x-text="tag"></span>
                    <button type="button" class="ml-2 text-lg text-red-500" @click="tags = tags.filter(i => i !== tag)">
                        &times;
                    </button>
                </span>
            </template>

            <input class="flex flex-1 items-center border-0 border-transparent bg-white pb-1 outline-none pt min-w-[10rem] focus:outline-none focus:ring-0 dark:bg-dark-800" placeholder="Add tag..."
                   @keydown.enter.prevent="if (newTag.trim() !== '') tags.push(newTag.trim()); newTag = ''"
                   x-model="newTag"
                   x-ref="input"
            >
        </div>

        <!-- Botão Limpar tudo à direita, dentro da mesma div -->
        <div x-show="tags.length > 0" class="absolute inset-y-0 right-2 flex items-center text-secondary-500 dark:text-dark-400" >
            <x-icon name="x-circle" class="h-5 w-5 cursor-pointer" x-on:click="tags = []" />
        </div>
    </div>
</div>




