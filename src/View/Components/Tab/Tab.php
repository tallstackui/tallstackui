<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('tab')]
class Tab extends Component implements Personalization
{
    public function __construct(public ?string $selected = null)
    {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-stretch rounded-lg overflow-hidden bg-white dark:bg-dark-700 p-1 hidden sm:block',
            'select' => 'sm:hidden py-3 px-4 w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 dark:bg-dark-700 dark:border-gray-700 dark:text-gray-400',
            'item' => [
                'wrapper' => 'inline-flex whitespace-nowrap cursor-pointer px-5 py-2 text-gray-700 transition rounded-lg soft-scrollbar hidden sm:block',
                'base' => 'flex flex-nowrap overflow-auto soft-scrollbar',
                'selected' => 'text-white bg-primary-500 font-medium dark:bg-primary-500',
                'unselected' => 'dark:text-dark-200 opacity-50',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.tab');
    }
}
