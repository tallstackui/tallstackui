<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('tab')]
class Tab extends BaseComponent implements Personalization
{
    public function __construct(public ?string $selected = null, public ?string $id = null)
    {
        $this->id ??= uniqid();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.tab.tab');
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
}
