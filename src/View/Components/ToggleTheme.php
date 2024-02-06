<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('toggleTheme')]
class ToggleTheme extends BaseComponent implements Personalization
{
    public function __construct(
        public ?bool $simple = false,
    ) {
       //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.toggle-theme');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'simple' => [
                'wrapper' => 'text-dark-500 pointer-events-none relative inline-block h-5 w-5 transition duration-200 ease-in-out dark:text-gray-200',
                'icon' => 'h-5 w-5'
            ],
            'toggle' => [
                'button' => 'focus:ring-primary-500 dark:ring-offset-dark-900 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2',
                'wrapper' => 'text-dark-500 pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                'icon' => 'h-4 w-4'
            ],
            'span' => 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
        ]);
    }
}
