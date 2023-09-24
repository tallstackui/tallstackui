<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Card extends Component
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

    public function baseClass(): string
    {
        return 'grow rounded-b-xl px-2 py-5 text-secondary-700 md:px-4';
    }

    public function cardElement(): array
    {
        //TODO: divide into header and footer class.
        return [
            'wrapper' => [
                'first' => 'flex justify-center gap-4',
                'second' => 'flex w-full flex-col rounded-lg bg-white shadow-md',
            ],
            'title' => [
                'wrapper' => Arr::toCssClasses([
                    'flex items-center justify-between',
                    'border-b px-4 py-2.5' => $this->header !== null,
                ]),
                'text' => 'font-medium text-md text-secondary-700',
            ],
            'footer' => [
                'wrapper' => 'rounded-lg rounded-t-none border-t px-4 py-4 bg-secondary-50 text-secondary-700 sm:px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
        ];
    }
}
