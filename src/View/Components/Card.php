<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Card extends Component implements Customizable
{
    public function __construct(
        public ?string $header = null,
        public ?string $footer = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.card');
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
            'base' => 'grow rounded-b-xl px-2 py-5 text-secondary-700 md:px-4',
            'wrapper' => [
                'first' => 'flex justify-center gap-4',
                'second' => 'flex w-full flex-col rounded-lg bg-white shadow-md',
            ],
            'title' => [
                'wrapper' => 'flex items-center justify-between border-b px-4 py-2.5',
                'text' => 'font-medium text-md text-secondary-700',
            ],
            'footer' => [
                'wrapper' => 'rounded-lg rounded-t-none border-t px-4 py-4 bg-secondary-50 text-secondary-700 sm:px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
        ]);
    }
}
