<?php

namespace TallStackUi\Components\Layout\SideBar\Separator;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('sideBar.separator')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?bool $simple = null,
        public ?bool $line = null,
        public ?bool $lineRight = null,
    ) {
        match (true) {
            $this->simple => $this->line = false,
            $this->line => $this->simple = false,
            $this->lineRight => $this->lineRight = true,
            default => $this->simple = true,
        };
    }

    public function blade(): View
    {
        return view('ts-ui::components.layout.sidebar.separator');
    }

    public function customization(): array
    {
        return Arr::dot([
            'simple' => [
                'wrapper' => 'flex py-2 pl-2',
                'base' => 'text-primary-600 dark:text-dark-100 text-base font-semibold leading-6 whitespace-nowrap overflow-hidden transition-all duration-150',
                'base.visible' => 'opacity-100 max-w-48',
                'base.hidden' => 'opacity-0 max-w-0',
            ],
            'line' => [
                'wrapper' => [
                    'first' => 'relative',
                    'second' => 'absolute inset-0 flex items-center',
                    'third' => 'relative flex justify-center',
                ],
                'border' => 'border-primary-100 dark:border-dark-500 w-full border-t',
                'base' => 'dark:bg-dark-700 text-primary-600 dark:text-dark-100 bg-white px-3 text-base font-semibold whitespace-nowrap overflow-hidden transition-all duration-150',
                'base.visible' => 'opacity-100 max-w-48',
                'base.hidden' => 'opacity-0 max-w-0',
            ],
            'line-right' => [
                'wrapper' => [
                    'first' => 'relative',
                    'second' => 'absolute inset-0 flex items-center',
                    'third' => 'relative flex justify-start',
                ],
                'border' => 'border-primary-100 dark:border-dark-500 w-full border-t',
                'base' => 'dark:bg-dark-700 text-primary-600 dark:text-dark-100 bg-white px-3 text-base font-semibold whitespace-nowrap overflow-hidden transition-all duration-150',
                'base.visible' => 'opacity-100 max-w-48',
                'base.hidden' => 'opacity-0 max-w-0',
            ],
        ]);
    }
}
