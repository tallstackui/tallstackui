<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('themeSwitch')]
class ThemeSwitch extends BaseComponent implements Personalization
{
    public function __construct(
        public ?bool $onlyIcons = false,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $xl = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->xl ? 'xl' : ($this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md')));
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.theme-switch');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
            'button' => 'flex items-center',
            'colors' => [
                'moon' => 'text-yellow-500',
                'sun' => 'text-blue-500',
            ],
            'simple' => [
                'wrapper' => 'text-dark-500 dark:text-dark-200 pointer-events-none relative inline-block transition duration-200 ease-in-out',
                'icons' => [
                    'sizes' => [
                        'xs' => 'h-3 w-3',
                        'sm' => 'h-4 w-4',
                        'md' => 'h-5 w-5',
                        'lg' => 'h-6 w-6',
                        'xl' => 'h-7 w-7',
                    ],
                ],
            ],
            'switch' => [
                'button' => 'focus:ring-primary-500 dark:ring-offset-dark-900 relative flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2',
                'wrapper' => 'text-dark-500 pointer-events-none relative inline-block transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                'on' => 'bg-primary-500',
                'off' => 'bg-gray-200',
                'icons' => [
                    'sizes' => [
                        'xs' => 'h-2 w-2',
                        'sm' => 'h-3 w-3',
                        'md' => 'h-4 w-4',
                        'lg' => 'h-5 w-5',
                        'xl' => 'h-6 w-6',
                    ],
                ],
                'sizes' => [
                    'xs' => 'h-3 w-5',
                    'sm' => 'h-4 w-7',
                    'md' => 'h-5 w-9',
                    'lg' => 'h-6 w-10',
                    'xl' => 'h-7 w-12',
                ],
                'translate' => [
                    'xs' => 'translate-x-2',
                    'sm' => 'translate-x-3',
                    'md' => 'translate-x-4',
                    'lg' => 'translate-x-4',
                    'xl' => 'translate-x-5',
                ],
            ],
        ]);
    }
}
