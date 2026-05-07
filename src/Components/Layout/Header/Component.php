<?php

namespace TallStackUi\Components\Layout\Header;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('layout.header')]
class Component extends TallStackUiComponent implements Customization
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
        return view('ts-ui::components.layout.header');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'dark:bg-dark-700 dark:border-dark-600 sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-300/10 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8',
            'button' => [
                'class' => 'md:hidden cursor-pointer',
                'icon.size' => 'h-6 w-6 text-gray-500 dark:text-white',
            ],
            'collapse' => [
                'class' => 'hidden md:block cursor-pointer',
                'icon' => 'bars-4',
                'icon.size' => 'h-6 w-6 text-gray-500 dark:text-white',
            ],
            'slots' => [
                'wrapper' => 'flex flex-1 items-center',
                'wrapper-right-only' => 'justify-end',
                'wrapper-multi-slot' => 'justify-between',
                'left' => 'flex items-center gap-2',
                'middle' => 'flex items-center',
                'right' => 'flex items-center',
            ],
        ]);
    }
}
