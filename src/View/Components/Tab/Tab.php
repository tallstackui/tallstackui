<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;

#[SoftPersonalization('tab')]
class Tab extends Component implements Personalization
{
    public function __construct(
        public ?string $selected = null,
        public ?string $id = null,
    ) {
        $this->id ??= uniqid();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'dark:bg-dark-700 w-full rounded-lg bg-white shadow-md',
            'body' => 'soft-scrollbar hidden flex-nowrap overflow-auto sm:flex',
            'item' => 'text-secondary-700 dark:text-dark-300 p-4',
            'divider' => 'hidden h-px border-0 bg-gray-300 dark:bg-gray-600 sm:block',
            'select' => 'focus:border-primary-500 focus:ring-primary-500 dark:bg-dark-700 dark:border-dark-500 w-full rounded-lg border-gray-200 px-4 py-3 dark:text-gray-400 sm:hidden',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.tab');
    }
}
