<?php

namespace TallStackUi\Components\Kbd;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('kbd')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $borderless = false,
        public ?string $tooltip = null,
        public ?string $size = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->xs ? 'xs' : 'sm'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.kbd.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'inline-flex items-center justify-center rounded-md border font-mono font-medium shadow bg-gray-100 border-gray-300 text-gray-600 dark:bg-dark-600 dark:border-dark-500 dark:text-dark-300',
                'sizes' => [
                    'xs' => 'text-xs px-1 py-0.5 min-w-5',
                    'sm' => 'text-sm px-1.5 py-0.5 min-w-6',
                    'md' => 'text-md px-2 py-1 min-w-7',
                    'lg' => 'text-lg px-2.5 py-1 min-w-8',
                ],
            ],
            'borderless' => 'border-transparent! shadow-none',
        ]);
    }
}
