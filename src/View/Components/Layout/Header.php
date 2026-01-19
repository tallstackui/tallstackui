<?php

namespace TallStackUi\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('layout.header')]
class Header extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ComponentSlot|string|null $left = null,
        public ComponentSlot|string|null $middle = null,
        public ComponentSlot|string|null $right = null,
        public ?bool $withoutMobileButton = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.layout.header');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'dark:bg-dark-700 dark:border-dark-600 sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-300/10 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8',
            'button' => [
                'class' => 'md:hidden cursor-pointer',
                'icon.size' => 'h-6 w-6 text-gray-500 dark:text-white',
            ],
            'slots' => [
                'left' => 'flex items-center gap-2',
                'middle' => 'flex items-center',
                'right' => 'flex items-center',
            ],
        ]);
    }
}
