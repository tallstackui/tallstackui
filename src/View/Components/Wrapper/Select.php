<?php

namespace TasteUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Select extends Component implements Customizable
{
    public function __construct(
        public bool $loading = false,
        public ?string $computed = null,
        public bool $error = false,
        public ?string $label = null,
        public ?string $hint = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.wrapper.select');
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
            'wrapper' => 'relative mt-2',
            'div' => [
                'base' => 'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6',
                'error' => 'text-red-600 ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
            ],
            'header' => 'relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-2 transition space-x-2',
            'buttons' => [
                'wrapper' => 'mr-1 flex items-center',
                'mark' => [
                    'base' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                    'error' => 'text-red-500',
                ],
                'down' => [
                    'base' => 'h-5 w-5 transition text-secondary-500',
                    'error' => 'text-red-500',
                ],
            ],
            'box' => [
                'wrapper' => 'absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'button' => [
                    'base' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'z-50 mt-1 max-h-60 w-full overflow-auto rounded-b-lg bg-white text-base soft-scrollbar focus:outline-none sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center p-4 space-x-4',
                        'base' => 'h-12 w-12 animate-spin text-primary-600',
                    ],
                    'item' => [
                        'wrapper' => 'relative cursor-pointer select-none px-2 py-2 text-gray-700 transition hover:bg-gray-100',
                        'base' => 'flex items-center justify-between',
                    ],
                ],
            ],
            'message' => 'block w-full pr-2 text-gray-700',
        ]);
    }
}
