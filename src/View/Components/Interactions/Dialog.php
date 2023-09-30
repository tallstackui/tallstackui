<?php

namespace TasteUi\View\Components\Interactions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Dialog extends Component implements Customizable
{
    public function __construct(
        public ?string $zIndex = 'z-50',
        public bool $blur = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.interactions.dialog');
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiMainClasses(),
        ];
    }

    public function tasteUiMainClasses(): array
    {
        return Arr::dot([
            'background' => 'fixed inset-0 bg-gray-400 bg-opacity-75 transition-opacity',
            'wrapper' => [
                'first' => Arr::toCssClasses([
                    'fixed inset-0 z-10 w-screen overflow-y-auto',
                    'backdrop-blur-sm' => $this->blur === true,
                ]),
                'second' => 'flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0',
                'third' => 'relative w-full max-w-sm transform overflow-hidden rounded-lg bg-white p-4 text-left shadow-xl transition-all sm:my-8',
            ],
            'icon' => [
                'wrapper' => 'mx-auto flex h-12 w-12 items-center justify-center rounded-full',
                'size' => 'h-8 w-8',
            ],
            'text' => [
                'wrapper' => 'mt-3 text-center sm:mt-5',
                'title' => 'text-lg font-semibold leading-6 text-gray-700',
                'description' => [
                    'wrapper' => 'mt-2',
                    'text' => 'text-sm text-gray-500',
                ],
            ],
            'buttons' => [
                'wrapper' => 'mt-5 space-y-2 sm:space-x-2 sm:space-y-0 sm:mt-6 sm:flex sm:justify-end',
                'cancel' => 'inline-flex w-full items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300',
                'confirm' => 'inline-flex w-full items-center justify-center rounded-md px-4 py-2 text-sm transition font-semibold text-white outline-none ease-in group focus:ring-2 focus:ring-offset-2',
            ],
        ]);
    }
}
