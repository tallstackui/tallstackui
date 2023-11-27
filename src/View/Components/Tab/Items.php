<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('tab.items')]
class Items extends Component implements Personalization
{
    public function __construct(
        public ?string $tab = null,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex items-center gap-2 whitespace-nowrap p-4 transition-all',
            'select' => 'text-primary-500 dark:text-dark-300 border-primary-500 dark:border-dark-300 group inline-flex items-center border-b-2 font-medium',
            'unselect' => 'dark:text-dark-500 hidden cursor-pointer border-b-2 border-transparent font-medium text-gray-400 sm:flex',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
