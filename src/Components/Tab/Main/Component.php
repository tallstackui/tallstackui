<?php

namespace TallStackUi\Components\Tab\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('tab')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?string $selected = null, public ?bool $scrollOnMobile = null, public ?bool $centered = null)
    {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.tab.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'base' => [
                'wrapper' => 'dark:bg-dark-700 w-full rounded-lg bg-white shadow-md',
                'padding' => 'p-2 sm:p-0',
                'body' => 'soft-scrollbar flex-nowrap overflow-auto flex',
                'body-mobile-show' => 'sm:flex',
                'centered' => 'justify-center',
                'content' => 'text-secondary-700 dark:text-dark-300 p-4',
                'divider' => 'h-px border-0 bg-gray-300 dark:bg-dark-600',
                'divider-mobile-show' => 'sm:block',
                'select' => 'focus:border-primary-500 focus:ring-primary-500 dark:bg-dark-700 dark:border-dark-500 w-full rounded-lg border-gray-200 px-4 py-3 dark:text-dark-400 sm:hidden',
            ],
            'item' => [
                'wrapper' => 'inline-flex items-center gap-2 whitespace-nowrap p-4 transition-all',
                'mobile-show' => 'sm:flex',
                'select' => 'text-primary-500 dark:text-dark-300 border-primary-500 dark:border-dark-300 group inline-flex cursor-pointer items-center border-b-2 font-medium',
                'unselect' => 'dark:text-dark-500 cursor-pointer border-b-2 border-transparent font-medium text-gray-400 flex',
            ],
        ]);
    }
}
