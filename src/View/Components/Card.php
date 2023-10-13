<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Card extends Component implements Customizable
{
    public function __construct(
        public ?string $header = null,
        public ?string $footer = null,
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
        return view('tallstack-ui::components.card');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex justify-center gap-4',
                'second' => 'flex w-full flex-col rounded-lg bg-white shadow-md',
            ],
            'header' => [
                'wrapper' => 'flex items-center justify-between border-b p-4',
                'text' => 'font-medium text-md text-secondary-700',
            ],
            'main' => 'grow rounded-b-xl px-2 py-5 text-secondary-700 md:px-4',
            'footer' => [
                'wrapper' => 'rounded-lg rounded-t-none border-t p-4 bg-secondary-50 text-secondary-700 sm:px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
        ]);
    }
}
