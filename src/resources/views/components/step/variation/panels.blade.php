<li class="relative cursor-pointer md:flex md:flex-1"
    x-on:click="selected = item.step">
    <div class="group flex w-full items-center">
        <span class="flex items-center px-6 py-4 text-sm font-medium">
            <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                  x-bind:class="{
                      'bg-primary-500 dark:border-primary-500 group-hover:bg-primary-400': selected >= item.step,
                      'border-2 border-primary-500 dark:border-dark-300': selected <= item.step,
                  }">
                <span x-text="item.step"
                      x-show="selected <= item.step"
                      x-bind:class="{
                          'text-primary-500 dark:text-dark-300': selected <= item.step,
                          'text-white': selected >= item.step,
                      }">
                </span>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('check')"
                                     x-show="selected > item.step"
                                     class="h-5 w-5 text-white" />
            </span>
            <div class="flex flex-col">
                <span class="ml-4 whitespace-nowrap text-base font-bold text-primary-500 dark:text-dark-100"
                      x-text="item.title"></span>
                <span class="ml-4 whitespace-nowrap text-xs font-medium text-gray-500 dark:text-dark-400"
                      x-text="item.description"></span>
            </div>
        </span>
    </div>
    <div x-show="item.step != steps.length"
         class="absolute right-0 top-0 hidden h-full w-5 md:block"
         aria-hidden="true">
        <svg class="h-full w-full text-dark-300 dark:text-dark-700"
             viewBox="0 0 22 80"
             fill="none"
             preserveAspectRatio="none">
            <path d="M0 -2L20 40L0 82"
                  vector-effect="non-scaling-stroke"
                  stroke="currentcolor"
                  stroke-linejoin="round" />
        </svg>
    </div>
</li>
