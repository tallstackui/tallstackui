<li class="cursor-pointer relative md:flex md:flex-1" x-on:click="currentStep = {{ $step }}">
    <div class="group flex w-full items-center">
        <span class="flex items-center px-6 py-4 text-sm font-medium">
            <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                  x-bind:class="{
                      'bg-primary-500 dark:border-primary-500 group-hover:bg-primary-400': currentStep >= {{ $step }},
                      'border-2 border-primary-500 dark:border-dark-300': currentStep <= {{ $step }},
                  }">
                <span x-show="currentStep <= {{ $step }}"
                      x-bind:class="{
                          'text-primary-500 dark:text-dark-300': currentStep <= {{ $step }},
                          'text-white': currentStep >= {{ $step }},
                      }">
                    {{ str_pad($step, 2, '0', STR_PAD_LEFT) }}
                </span>
                <x-dynamic-component :component="TallStackUi::component('icon')"
                                     :icon="TallStackUi::icon('check')"
                                     x-show="currentStep > {{ $step }}"
                                     class="w-5 h-5 text-white" />
            </span>
            <div class="flex flex-col">
                <span class="ml-4 text-base font-bold text-primary-500 dark:text-dark-100 whitespace-nowrap">{{ $title }}</span>
                <span class="ml-4 text-xs font-medium text-gray-500 dark:text-dark-400 whitespace-nowrap">{{ $description }}</span>
            </div>
        </span>
    </div>
    <div x-show="{{ $step }} != total" class="absolute right-0 top-0 hidden h-full w-5 md:block" aria-hidden="true">
        <svg class="h-full w-full text-dark-300 dark:text-dark-700" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
            <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor" stroke-linejoin="round" />
        </svg>
    </div>
</li>