<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;

#[SoftPersonalization('card')]
class Card extends Component implements Personalization
{
    public function __construct(public ?string $header = null, public ?string $footer = null)
    {
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
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b border-b-gray-100 p-4',
                'text' => 'text-md text-secondary-700 dark:text-dark-300 font-medium',
            ],
            'body' => 'text-secondary-700 dark:text-dark-300 grow rounded-b-xl px-4 py-5',
            'footer' => [
                'wrapper' => 'text-secondary-700 dark:text-dark-300 dark:border-t-dark-600 rounded-lg rounded-t-none border-t p-4 px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.card');
    }
}
