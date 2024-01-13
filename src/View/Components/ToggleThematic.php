<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('togglethematic')]
class ToggleThematic extends BaseComponent implements Personalization
{
    public function __construct(
        public ?bool $simple = null,
        public ?bool $toggle = null,
        public ?bool $colorful = null,
        public ?string $style = null,
    ) {
        $this->style = $this->toggle ? 'toggle' : 'simple';
        $this->colorful = $this->colorful ?: false;
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.toggle-thematic');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'relative inline-block select-none',
                'second' => 'bg-dark-200 dark:bg-dark-700 flex h-6 w-full cursor-pointer items-center justify-between overflow-hidden rounded-full p-1',
            ],
            'switch' => 'border-dark-200 dark:border-dark-700 duration-400 absolute z-10 block h-6 w-6 transform cursor-pointer rounded-full border-4 bg-white transition',
            'icons' => [
                'dark' => [
                    'name' => 'moon',
                    'size' => 'h-5 w-5',
                    'color' => 'text-dark-500 dark:text-dark-400',
                    'colorful' => 'text-blue-300',
                ],
                'light' => [
                    'name' => 'sun',
                    'size' => 'h-5 w-5',
                    'color' => 'text-dark-400 dark:text-dark-100',
                    'colorful' => 'text-yellow-500 dark:text-yellow-400',
                ],
            ],
        ]);
    }
}
