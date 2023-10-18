<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Card extends Component implements Personalize
{
    public function __construct(
        public ?string $header = null,
        public ?string $footer = null,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex justify-center gap-4',
                'second' => 'dark:bg-dark-700 flex w-full flex-col rounded-lg bg-white shadow-md',
            ],
            'header' => [
                'wrapper' => 'dark:border-b-dark-500 flex items-center justify-between border-b p-4',
                'text' => 'text-md text-secondary-700 dark:text-dark-300 font-medium',
            ],
            'body' => 'text-secondary-700 dark:text-dark-300 grow rounded-b-xl px-2 py-5 md:px-4',
            'footer' => [
                'wrapper' => 'bg-secondary-50 text-secondary-700 dark:text-dark-300 dark:border-t-dark-500 dark:bg-dark-600 rounded-lg rounded-t-none border-t p-4 sm:px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.card');
    }
}
