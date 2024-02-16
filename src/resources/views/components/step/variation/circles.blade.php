<li class="md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block cursor-pointer transition-all" x-on:click="selected = item.step">
    <div class="min-w-8 min-h-8 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-md align-middle">
        <span class="size-8 flex justify-center items-center flex-shrink-0 font-bold rounded-full "
              x-bind:class="{
                  'bg-primary-500 text-white': selected > item.step,
                  'border-2 border-primary-500 text-primary-500': selected == item.step,
                  'border-2 border-gray-300 text-gray-500 dark:text-dark-300 dark:border-dark-500': selected < item.step,
              }">
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('check')"
                                     x-show="selected > item.step"
                                     class="w-5 h-5 text-white" />
            <span x-show="selected == item.step" 
                  class="h-2.5 w-2.5 rounded-full bg-primary-500 transition-all"
                  aria-hidden="true"></span>
            <span x-show="selected < item.step" x-text="item.step"></span>
        </span>
        <div x-show="item.step != steps.length"
             class="w-0.5 h-full md:mt-0 md:w-full md:h-0.5 md:flex-1 group-last:hidden transition-all"
             x-bind:class="{
                 'bg-primary-500': selected > item.step,
                 'bg-gray-200 dark:bg-dark-500': selected <= item.step,
             }">
        </div>
    </div>
    <div class="grow md:grow-0 md:mt-3 pb-5 transition-all">
        <span x-text="item.title"
              class="block text-base font-medium transition-all"
              x-bind:class="{
                  'text-primary-500': selected > item.step,
                  'text-gray-600 dark:text-dark-200': selected <= item.step,
              }"></span>
        <span x-text="item.description" class="text-sm font-medium text-gray-500 dark:text-dark-400 transition-all"></span>
    </div>
</li>
