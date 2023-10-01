<?php

namespace TasteUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;
use TasteUi\Contracts\Customizable;

class Toast extends Component implements Customizable
{
    public function __construct(
        public string $zIndex = 'z-50',
        public ?string $position = 'top-right',
    ) {
        if (! in_array($position, ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            throw new InvalidArgumentException("The position must be one of the following: ['top-right', 'top-left', 'bottom-right', 'bottom-left']");
        }
    }

    public function render(): View
    {
        return view('taste-ui::components.interactions.toast');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => Arr::toCssClasses([
                    'pointer-events-none fixed inset-0 flex flex-col items-end justify-end gap-y-2 px-4 py-4',
                    'md:justify-start' => str_contains($this->position, 'top-'),
                    'md:justify-end' => str_contains($this->position, 'bottom-'),
                    $this->zIndex,
                ]),
                'second' => Arr::toCssClasses([
                    'flex w-full flex-col items-center space-y-4',
                    'md:items-start' => $this->position === 'top-left' || $this->position === 'bottom-left',
                    'md:items-end' => $this->position === 'top-right' || $this->position === 'bottom-right',
                ]),
                'third' => 'pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'fourth' => 'flex items-start p-4',
            ],
            'icon' => [
                'size' => 'h-6 w-6',
            ],
            'content' => [
                'wrapper' => 'ml-3 w-0 flex-1 pt-0.5',
                'text' => 'text-sm text-gray-800',
                'description' => 'mt-1 text-sm text-gray-700',
            ],
            'buttons' => [
                'wrapper' => 'mt-3 flex gap-x-3',
                'confirm' => 'rounded-md bg-white text-sm font-semibold text-primary-600 focus:outline-none',
                'cancel' => 'rounded-md bg-white text-sm font-medium text-secondary-700 focus:outline-none',
                'close' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'base' => 'inline-flex rounded-md bg-white text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
            ],
        ]);
    }
}
