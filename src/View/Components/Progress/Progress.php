<?php

namespace TallStackUi\View\Components\Progress;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('progress')]
class Progress extends BaseComponent implements Personalization
{
    public function __construct(
        public string|int|null $percent = null,
        public ?string $title = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $simple = false,
        public ?bool $floating = false,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?string $color = 'primary',
        public ?bool $withoutText = false,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $variation = null,
    ) {
        $this->variation = $this->title ? 'title' : ($this->floating ? 'floating' : 'simple');

        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->style = $this->light ? 'light' : 'solid';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.progress.progress');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'simple' => [
                'wrapper' => 'flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'progress' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500',
            ],
            'floating' => [
                'wrapper' => 'dark:border-dark-700 dark:bg-dark-800 mb-2 inline-block rounded-lg border border-gray-300 bg-gray-200 px-1.5 py-0.5 text-xs font-medium dark:text-white',
                'progress' => 'flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'float' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500',
            ],
            'title' => [
                'wrapper' => 'mb-2 flex items-center justify-between',
                'title' => 'text-sm font-semibold text-gray-800 dark:text-white',
                'progress' => 'flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'bar' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500',
                'percent' => 'text-sm text-gray-800 dark:text-white',
            ],
            'sizes' => [
                'xs' => 'h-2.5',
                'sm' => 'h-3',
                'md' => 'h-4',
                'lg' => 'h-5',
            ],
        ]);
    }
}
