<?php

namespace TallStackUi\View\Components\Step;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('step')]
class Step extends BaseComponent implements Personalization
{
    public function __construct(
        public ?int $selected = null,
        public ?bool $panels = false,
        public ?bool $circles = false,
        public ?bool $simple = false,
        public ?bool $helpers = false,
        public ?bool $navigate = false,
        public ?bool $navigatePrevious = false,
        public ?string $variation = null,
        #[SkipDebug]
        public ComponentSlot|string|null $finish = null,
    ) {
        $this->variation = $this->panels ? 'panels' : ($this->circles ? 'circles' : 'simple');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.step.step');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'panels' => 'rounded-md border border-gray-300 dark:border-dark-700 dark:divide-dark-700 md:flex overflow-auto soft-scrollbar mb-2',
                'simple' => 'space-y-2 md:flex md:gap-8 md:space-y-0 overflow-auto soft-scrollbar pb-2',
                'circles' => 'relative flex flex-col md:flex-row',
            ],
            'circles' => [
                'li' => 'group flex flex-1 gap-x-2 transition-colors md:block md:shrink md:basis-0',
                'wrapper' => 'min-w-8 min-h-8 text-md flex flex-col items-center align-middle md:inline-flex md:w-full md:flex-row md:flex-wrap',
                'check' => 'h-5 w-5 text-white',
                'circle' => [
                    'wrapper' => 'w-8 h-8 flex flex-shrink-0 items-center justify-center rounded-full font-bold',
                    'inactive' => 'border-2 border-gray-300 text-gray-500 dark:text-dark-300 dark:border-dark-500',
                    'current' => 'border-2 border-primary-500 text-primary-500',
                    'border' => 'border-2 border-green-600',
                    'active' => 'bg-green-600 text-white',
                ],
                'highlighter' => [
                    'wrapper' => 'h-2.5 w-2.5 rounded-full transition-colors',
                    'current' => ' bg-primary-500',
                    'active' => 'bg-green-600',
                ],
                'divider' => [
                    'wrapper' => 'h-full w-0.5 transition-colors group-last:hidden md:mt-0 md:h-0.5 md:w-full md:flex-1',
                    'inactive' => 'bg-gray-200 dark:bg-dark-500',
                    'active' => 'bg-green-600',
                ],
                'text' => [
                    'wrapper' => 'grow pb-2 transition-colors md:mt-3 md:grow-0',
                    'title' => 'block text-base font-medium transition-colors text-gray-600 dark:text-dark-300',
                    'description' => 'text-sm font-medium text-gray-500 transition-colors dark:text-dark-400',
                ],
            ],
            'simple' => [
                'li' => 'transition-all md:flex-1',
                'bar' => [
                    'wrapper' => 'flex flex-col py-2 pl-4 md:border-l-0 md:border-t-4 md:pb-0 md:pl-0 md:pt-4',
                    'inactive' => 'group border-l-4 border-dark-200 dark:border-dark-700',
                    'current' => 'border-l-4 border-primary-500',
                    'active' => 'border-l-4 border-green-600',
                ],
                'text' => [
                    'title' => [
                        'wrapper' => 'whitespace-nowrap text-base font-bold',
                        'inactive' => 'text-gray-600 dark:text-dark-300',
                        'current' => 'text-primary-500',
                        'active' => 'text-green-600',
                    ],
                    'description' => 'whitespace-nowrap text-sm font-medium text-gray-500 dark:text-dark-400',
                ],
            ],
            'panels' => [
                'li' => 'relative md:flex md:flex-1 border-b last:border-b-0 border-gray-300 md:border-0',
                'wrapper' => 'group flex w-full items-center',
                'check' => 'h-5 w-5 text-white',
                'item' => 'flex items-center px-6 py-4 text-sm font-medium',
                'circle' => [
                    'wrapper' => 'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full',
                    'inactive' => 'border-2 border-primary-500 dark:border-dark-300',
                    'current' => 'bg-primary-500 dark:border-primary-500 group-hover:bg-primary-400',
                    'active' => 'bg-green-600 dark:border-green-600 group-hover:bg-green-500',
                ],
                'divider' => [
                    'wrapper' => 'absolute right-0 top-0 hidden h-full w-5 md:block',
                    'svg' => 'h-full w-full text-dark-300 dark:text-dark-700',
                ],
                'text' => [
                    'number' => [
                        'active' => 'text-primary-500 dark:text-dark-300',
                        'inactive' => 'text-white',
                    ],
                    'title' => [
                        'wrapper' => 'ml-4 whitespace-nowrap text-base font-bold',
                        'inactive' => 'text-primary-500 dark:text-dark-100',
                        'active' => 'text-green-600 dark:text-dark-100',
                    ],
                    'description' => 'ml-4 whitespace-nowrap text-xs font-medium text-gray-500 dark:text-dark-400',
                ],
            ],
            'content' => 'my-2',
            'button' => [
                'wrapper' => 'dark:text-dark-400 mb-2 me-2 inline-flex select-none items-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:hover:border-gray-600 dark:hover:bg-gray-700',
                'icon' => 'dark:text-dark-300 h-4 w-4',
            ],
        ]);
    }
}
