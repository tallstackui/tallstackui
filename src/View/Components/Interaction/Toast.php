<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\Contracts\Customizable;

class Toast extends Component implements Customizable
{
    public function __construct(
        public ?string $zIndex = null,
        public ?string $position = null,
        public bool $square = false,
    ) {
        $this->zIndex = config('tallstackui.personalizations.toast.z-index');
        $this->position = config('tallstackui.personalizations.toast.position');
        $this->square = config('tallstackui.personalizations.toast.square');

        if (! in_array($this->position, ['top-right', 'top-left', 'bottom-right', 'bottom-left'])) {
            throw new InvalidArgumentException("The position must be one of the following: ['top-right', 'top-left', 'bottom-right', 'bottom-left']");
        }

        if (! str_starts_with($this->zIndex, 'z-')) {
            throw new InvalidArgumentException('The z-index must start with z- prefix.');
        }
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.interactions.toast');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => Arr::toCssClasses([
                    'pointer-events-none fixed inset-0 flex flex-col items-end justify-end gap-y-2 px-4 py-4',
                    'md:justify-start' => str_contains($this->position, 'top-'),
                    'md:justify-end' => str_contains($this->position, 'bottom-'),
                ]),
                'second' => Arr::toCssClasses([
                    'flex w-full flex-col items-center space-y-4',
                    'md:items-start' => $this->position === 'top-left' || $this->position === 'bottom-left',
                    'md:items-end' => $this->position === 'top-right' || $this->position === 'bottom-right',
                ]),
                'third' => Arr::toCssClasses([
                    'pointer-events-auto w-full max-w-sm overflow-hidden bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                    'rounded-xl' => ! $this->square,
                ]),
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
                'confirm' => 'bg-white text-sm font-semibold text-primary-600 focus:outline-none',
                'cancel' => 'bg-white text-sm font-medium text-secondary-700 focus:outline-none',
                'close' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'base' => 'inline-flex bg-white text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
            ],
        ]);
    }
}
