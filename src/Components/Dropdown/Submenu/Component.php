<?php

namespace TallStackUi\Components\Dropdown\Submenu;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Traits\DropdownSharedTransitions;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dropdown.submenu')]
class Component extends TallStackUiComponent implements Customization
{
    use DropdownSharedTransitions;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?bool $separator = false,
    ) {
        $this->position = $this->position === 'right' ? 'right-start' : 'left-start';
    }

    public function blade(): View
    {
        return view('ts-ui::components.dropdown.submenu');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center gap-1',
            'item' => [
                'base' => 'text-secondary-600 dark:hover:bg-dark-600 dark:text-dark-300 dark:focus:bg-dark-600 flex w-full cursor-pointer items-center justify-between whitespace-nowrap transition-colors duration-150 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden',
                'sizes' => [
                    'xs' => "[[data-tsui-dropdown-size='xs']_&]:px-2 [[data-tsui-dropdown-size='xs']_&]:py-1 [[data-tsui-dropdown-size='xs']_&]:text-xs",
                    'sm' => "[[data-tsui-dropdown-size='sm']_&]:px-3 [[data-tsui-dropdown-size='sm']_&]:py-1.5 [[data-tsui-dropdown-size='sm']_&]:text-sm",
                    'md' => "[[data-tsui-dropdown-size='md']_&]:px-4 [[data-tsui-dropdown-size='md']_&]:py-2 [[data-tsui-dropdown-size='md']_&]:text-sm",
                    'lg' => "[[data-tsui-dropdown-size='lg']_&]:px-5 [[data-tsui-dropdown-size='lg']_&]:py-2.5 [[data-tsui-dropdown-size='lg']_&]:text-base",
                ],
            ],
            'border' => 'dark:border-t-dark-600 border-t border-t-gray-100',
            'icon' => [
                'sizes' => [
                    'xs' => "[[data-tsui-dropdown-size='xs']_&]:h-3.5 [[data-tsui-dropdown-size='xs']_&]:w-3.5",
                    'sm' => "[[data-tsui-dropdown-size='sm']_&]:h-4 [[data-tsui-dropdown-size='sm']_&]:w-4",
                    'md' => "[[data-tsui-dropdown-size='md']_&]:h-5 [[data-tsui-dropdown-size='md']_&]:w-5",
                    'lg' => "[[data-tsui-dropdown-size='lg']_&]:h-5 [[data-tsui-dropdown-size='lg']_&]:w-5",
                ],
            ],
            'submenu' => [
                'left' => 'mr-2 h-4 w-4',
                'right' => 'ml-2 h-4 w-4',
            ],
            'floating' => [
                'default' => collect(app(Floating::class)->customization())->get('wrapper'),
                'widths' => [
                    'xxs' => "data-[tsui-dropdown-width='xxs']:w-32",
                    'xs' => "data-[tsui-dropdown-width='xs']:w-40",
                    'sm' => "data-[tsui-dropdown-width='sm']:w-48",
                    'md' => "data-[tsui-dropdown-width='md']:w-56",
                    'lg' => "data-[tsui-dropdown-width='lg']:w-64",
                    'xl' => "data-[tsui-dropdown-width='xl']:w-72",
                    '2xl' => "data-[tsui-dropdown-width='2xl']:w-80",
                ],
            ],
            'slot' => 'overflow-hidden rounded-md',
        ]);
    }
}
