<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('tab.items')]
class Items extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $tab = null,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.tab.items');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex items-center gap-2 whitespace-nowrap p-4 transition-all',
            'select' => 'text-primary-500 dark:text-dark-300 border-primary-500 dark:border-dark-300 group inline-flex cursor-pointer items-center border-b-2 font-medium',
            'unselect' => 'dark:text-dark-500 hidden cursor-pointer border-b-2 border-transparent font-medium text-gray-400 sm:flex',
        ]);
    }
}
