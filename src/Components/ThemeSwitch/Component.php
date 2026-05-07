<?php

namespace TallStackUi\Components\ThemeSwitch;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('themeSwitch')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?bool $simple = false,
        public ?bool $onlyIcons = false,
        public ?bool $block = false,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $xl = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $variation = null,
    ) {
        $this->size = $this->xl ? 'xl' : ($this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md')));

        $this->variation = $this->simple ? 'simple' : 'segmented';

        if ($this->onlyIcons && ! $this->simple) {
            __ts_validation_exception($this, 'The [only-icons] property requires [simple] to be enabled.');
        }

        if ($this->block && $this->simple) {
            __ts_validation_exception($this, 'The [block] property is not supported with [simple] variation.');
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.theme-switch.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
            'button' => 'flex items-center cursor-pointer',
            'colors' => [
                'moon' => 'text-yellow-500',
                'sun' => 'text-blue-500',
            ],
            'simple' => [
                'wrapper' => 'text-dark-500 dark:text-dark-200 pointer-events-none relative inline-block',
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
            'segmented' => [
                'wrapper' => 'dark:bg-dark-600 inline-flex items-center gap-1 rounded-lg bg-gray-100 p-1',
                'wrapper-block' => 'w-full',
                'button' => 'cursor-pointer rounded-md p-1.5 transition-colors',
                'active' => 'dark:bg-dark-500 bg-white',
                'inactive' => 'dark:text-dark-300 dark:hover:text-dark-100 text-gray-500 hover:text-gray-700',
                'colors' => [
                    'moon' => 'text-blue-500',
                    'sun' => 'text-yellow-500',
                    'system' => 'dark:text-white text-gray-500',
                ],
                'icons' => [
                    'sizes' => [
                        'xs' => 'h-3 w-3',
                        'sm' => 'h-3.5 w-3.5',
                        'md' => 'h-4 w-4',
                        'lg' => 'h-5 w-5',
                        'xl' => 'h-6 w-6',
                    ],
                ],
            ],
            'segmented-block' => 'flex flex-1 items-center justify-center focus:outline-hidden',
            'transition' => [
                'enter' => [
                    'class' => 'duration-200 ease-in',
                    'to' => 'opacity-100',
                ],
                'leave' => [
                    'class' => 'duration-100 ease-out',
                    'to' => 'opacity-0',
                ],
            ],
            'switch' => [
                'button' => 'focus:ring-primary-500 dark:ring-offset-dark-900 relative shrink-0 cursor-pointer rounded-full border-2 border-transparent focus:outline-hidden focus:ring-0 focus:ring-offset-0',
                'wrapper' => 'text-dark-500 pointer-events-none relative inline-block transform rounded-full bg-white shadow ring-0',
                'on' => 'bg-primary-500',
                'off' => 'bg-gray-200',
                'translate-x-0' => 'translate-x-0',
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
