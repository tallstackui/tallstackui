<li class="cursor-pointer md:flex-1 transition-all" x-on:click="selected = item.step">
    <div aria-current="step" 
         class="flex flex-col py-2 pl-4 md:border-l-0 md:border-t-4 md:pb-0 md:pl-0 md:pt-4"
         x-bind:class="{
            'border-l-4 border-primary-500': selected >= item.step,
            'group border-l-4 border-dark-200 dark:border-dark-700': selected < item.step,
         }">
        <span x-text="item.title" 
              class="text-base font-bold whitespace-nowrap" 
              x-bind:class="{
                    'text-primary-500': selected >= item.step,
                    'text-gray-600 dark:text-dark-300 group-hover:text-dark-500': selected < item.step,
              }"></span>
        <span x-text="item.description"  class="text-sm font-medium whitespace-nowrap text-gray-500 dark:text-dark-400"></span>
    </div>
</li>