<?php

namespace TallStackUi\Components\Dropdown\Items;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\DropdownItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dropdown.items')]
#[PassThroughRuntime(DropdownItemsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $href = null,
        public ?bool $separator = false,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('ts-ui::components.dropdown.items');
    }

    public function customization(): array
    {
        return Arr::dot([
            'item' => [
                'base' => 'text-secondary-600 dark:text-dark-300 dark:hover:bg-dark-600 dark:focus:bg-dark-600 flex w-full cursor-pointer items-center whitespace-nowrap transition-colors duration-150 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden',
                'sizes' => [
                    'xs' => "[[data-tsui-dropdown-size='xs']_&]:px-2 [[data-tsui-dropdown-size='xs']_&]:py-1 [[data-tsui-dropdown-size='xs']_&]:text-xs",
                    'sm' => "[[data-tsui-dropdown-size='sm']_&]:px-3 [[data-tsui-dropdown-size='sm']_&]:py-1.5 [[data-tsui-dropdown-size='sm']_&]:text-sm",
                    'md' => "[[data-tsui-dropdown-size='md']_&]:px-4 [[data-tsui-dropdown-size='md']_&]:py-2 [[data-tsui-dropdown-size='md']_&]:text-sm",
                    'lg' => "[[data-tsui-dropdown-size='lg']_&]:px-5 [[data-tsui-dropdown-size='lg']_&]:py-2.5 [[data-tsui-dropdown-size='lg']_&]:text-base",
                ],
            ],
            'border' => 'dark:border-t-dark-600 border-t border-t-gray-100',
            'icon' => [
                'base' => 'dark:text-dark-300 text-gray-500',
                'sizes' => [
                    'xs' => "[[data-tsui-dropdown-size='xs']_&]:h-3.5 [[data-tsui-dropdown-size='xs']_&]:w-3.5",
                    'sm' => "[[data-tsui-dropdown-size='sm']_&]:h-4 [[data-tsui-dropdown-size='sm']_&]:w-4",
                    'md' => "[[data-tsui-dropdown-size='md']_&]:h-5 [[data-tsui-dropdown-size='md']_&]:w-5",
                    'lg' => "[[data-tsui-dropdown-size='lg']_&]:h-5 [[data-tsui-dropdown-size='lg']_&]:w-5",
                ],
            ],
        ]);
    }
}
