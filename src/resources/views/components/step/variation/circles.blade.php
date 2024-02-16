<li class="group flex flex-1 cursor-pointer gap-x-2 transition-all md:block md:shrink md:basis-0"
    x-on:click="selected = item.step">
    <div
         class="min-w-8 min-h-8 text-md flex flex-col items-center align-middle md:inline-flex md:w-full md:flex-row md:flex-wrap">
        <span class="size-8 flex flex-shrink-0 items-center justify-center rounded-full font-bold"
              x-bind:class="{
                  'bg-primary-500 text-white': selected > item.step,
                  'border-2 border-primary-500 text-primary-500': selected == item.step,
                  'border-2 border-gray-300 text-gray-500 dark:text-dark-300 dark:border-dark-500': selected < item
                      .step,
              }">
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 :icon="TallStackUi::icon('check')"
                                 x-show="selected > item.step"
                                 class="h-5 w-5 text-white" />
            <span x-show="selected == item.step"
                  class="h-2.5 w-2.5 rounded-full bg-primary-500 transition-all"
                  aria-hidden="true"></span>
            <span x-show="selected < item.step"
                  x-text="item.step"></span>
        </span>
        <div x-show="item.step != steps.length"
             class="h-full w-0.5 transition-all group-last:hidden md:mt-0 md:h-0.5 md:w-full md:flex-1"
             x-bind:class="{
                 'bg-primary-500': selected > item.step,
                 'bg-gray-200 dark:bg-dark-500': selected <= item.step,
             }">
        </div>
    </div>
    <div class="grow pb-5 transition-all md:mt-3 md:grow-0">
        <span x-text="item.title"
              class="block text-base font-medium transition-all"
              x-bind:class="{
                  'text-primary-500': selected > item.step,
                  'text-gray-600 dark:text-dark-200': selected <= item.step,
              }"></span>
        <span x-text="item.description"
              class="text-sm font-medium text-gray-500 transition-all dark:text-dark-400"></span>
    </div>
</li>
