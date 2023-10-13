<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Select extends Component implements Customizable
{
    public function __construct(
        public bool $loading = false,
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $after = null,
        public bool $error = false,
    ) {
        //
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.select');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => 'relative',
            'input' => [
                'wrapper' => 'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6',
                'error' => 'text-red-600 ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
            ],
            'header' => 'relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-2 transition space-x-2',
            'buttons' => [
                'wrapper' => 'mr-1 flex items-center',
                'x-mark' => [
                    'icon' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                    'icon.error' => 'text-red-500',
                ],
                'up-down' => [
                    'icon' => 'h-5 w-5 transition ext-secondary-500',
                    'icon.error' => 'text-red-500',
                ],
            ],
            'box' => [
                'wrapper' => 'absolute z-10 mt-1 w-full rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'button' => [
                    'class' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'z-50 mt-1 max-h-60 w-full overflow-auto rounded-b-lg bg-white text-base soft-scrollbar focus:outline-none sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center p-4 space-x-4',
                        'class' => 'h-12 w-12 animate-spin text-primary-600',
                    ],
                    'item' => [
                        'wrapper' => 'relative cursor-pointer select-none px-2 py-2 text-gray-700 transition hover:bg-gray-100',
                        'class' => 'flex items-center justify-between',
                    ],
                ],
            ],
            'message' => 'block w-full pr-2 text-gray-700',
        ]);
    }
}
