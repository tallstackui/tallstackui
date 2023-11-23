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
            'wrapper' => 'flex items-stretch rounded-lg overflow-hidden',
            'select' => 'sm:hidden py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-dark-700 dark:border-gray-700 dark:text-gray-400',
            'item' => [
                'wrapper' => 'inline-flex whitespace-nowrap cursor-pointer px-5 py-3 text-gray-700 transition rounded-lg soft-scrollbar hidden sm:block',
                'selected' => 'text-primary dark:bg-dark-700 dark:text-dark-300 bg-white font-medium',
                'unselected' => 'dark:text-dark-200 opacity-50',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.tab');
    }
}
