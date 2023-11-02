<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;

class Tab extends Component implements Personalization
{
    public function __construct(public ?string $selected = null)
    {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => '-mb-px flex items-stretch overflow-auto',
            'item' => [
                'wrapper' => 'inline-flex cursor-pointer justify-center truncate px-5 py-2.5 text-gray-700 transition rounded-t-lg',
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
