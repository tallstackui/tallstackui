<style>
    .tags-input {
        display: flex;
        flex-wrap: wrap;
        background-color: #fff;
        border-width: 1px;
        border-radius: .25rem;
        padding-left: .5rem;
        padding-right: 1rem;
        padding-top: .5rem;
        padding-bottom: .25rem;
    }

    .tags-input-tag {
        display: inline-flex;
        line-height: 1;
        align-items: center;
        font-size: .875rem;
        background-color: #bcdefa;
        color: #1c3d5a;
        border-radius: .25rem;
        user-select: none;
        padding: .25rem;
        margin-right: .5rem;
        margin-bottom: .25rem;
    }

    .tags-input-tag:last-of-type {
        margin-right: 0;
    }

    .tags-input-remove {
        color: #2779bd;
        font-size: 1.125rem;
        line-height: 1;
    }

    .tags-input-remove:first-child {
        margin-right: .25rem;
    }

    .tags-input-remove:last-child {
        margin-left: .25rem;
    }

    .tags-input-remove:focus {
        outline: 0;
    }

    .tags-input-text {
        flex: 1;
        outline: 0;
        padding-top: .25rem;
        padding-bottom: .25rem;
        margin-left: .5rem;
        margin-bottom: .25rem;
        min-width: 10rem;
    }

    .py-16 {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
</style>

<div x-data="{tags: ['what', 'is', 'up'], newTag: '', inputName: 'foo' }" class="min-h-screen bg-white px-8 py-16 dark:bg-dark-800">
    <template x-for="tag in tags">
        <input type="hidden" x-bind:name="inputName + '[]'" x-bind:value="tag">
    </template>

    <div class="mx-auto w-full max-w-sm">
        <div @class([
                'flex flex-wrap items-center',
                'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md px-2 ring-1 transition focus-within:ring-2',
                'w-full border-0 bg-transparent p-1 py-1.5 ring-0 focus:ring-transparent sm:text-sm sm:leading-6',
                'dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 text-gray-600 ring-gray-300 placeholder:text-gray-400',
                'dark:bg-dark-800 bg-white'
            ])>
            <div class="flex flex-wrap items-center space-x-2">
                <template x-for="tag in tags" :key="tag">
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200 space-x-1 dark:text-dark-100 dark:bg-dark-700 dark:ring-dark-600">
                        <span x-text="tag"></span>
                        <x-icon name="x-mark"
                                class="h-4 w-4 text-gray-500 hover:text-red-500 cursor-pointer"
                                x-on:click="tags = tags.filter(i => i !== tag)" />
                    </span>
                </template>
            </div>

            <input @class([
                    'flex flex-1',
                    'w-full border-0 bg-transparent ml-1 py-1.5 ring-0 ring-inset focus:ring-transparent sm:text-sm sm:leading-6',
                    'focus:ring-0 dark:ring-dark-600 dark:text-dark-300 dark:placeholder-dark-500 text-gray-600 ring-gray-300 placeholder:text-gray-400',
                   ]) placeholder="Add tag..."
                   x-on:keydown="async () => {
                        if ($event.key === 'Enter' || $event.key === ',') {
                            if (newTag.trim() !== '') {
                                let tag = newTag.trim();
                                await $nextTick(() => tags.push(tag.replace(',', '')));
                                newTag = '';
                            }
                        }
                   }"
                   x-model="newTag"
            >
        </div>
    </div>
</div>
