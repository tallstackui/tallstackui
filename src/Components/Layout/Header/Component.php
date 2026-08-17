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
    public const SIZES = ['sm', 'md', 'lg', 'xl'];

    public function __construct(
        public ComponentSlot|string|null $left = null,
        public ComponentSlot|string|null $middle = null,
        public ComponentSlot|string|null $right = null,
        public ?bool $withoutMobileButton = null,
        public ?string $size = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $xl = null,
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
            'wrapper' => [
                'base' => 'dark:bg-dark-800 dark:border-dark-700 sticky top-0 z-40 flex shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 sm:gap-x-6 sm:px-6 lg:px-8 tsui-scrollbar-bleed',
                'sizes' => [
                    'sm' => 'h-14',
                    'md' => 'h-16',
                    'lg' => 'h-20',
                    'xl' => 'h-24',
                ],
            ],
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

    protected function setup(): void
    {
        $this->size = match (true) {
            $this->xl === true => 'xl',
            $this->lg === true => 'lg',
            $this->md === true => 'md',
            $this->sm === true => 'sm',
            default => $this->size ?? __ts_get_component_configuration(self::class, 'size') ?? 'md',
        };
    }

    protected function validate(): void
    {
        if (! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of: '.implode(', ', self::SIZES));
        }
    }
}
