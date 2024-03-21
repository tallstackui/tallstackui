<?php

namespace TallStackUi\View\Components\Progress;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Progress\Traits\SetupProgress;

#[SoftPersonalization('progress')]
class Progress extends BaseComponent implements Personalization
{
    use SetupProgress;

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
        public ?ComponentSlot $footer = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $variation = null,
    ) {
        $this->variation = $this->title ? 'title' : ($this->floating ? 'floating' : 'simple');

        $this->setup();
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
                'wrapper' => 'dark:border-dark-600 dark:bg-dark-700 mb-2 inline-block rounded-lg border border-gray-300 bg-gray-200 px-1.5 py-0.5 text-xs font-medium text-gray-700 dark:text-dark-300',
                'progress' => 'flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'float' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500',
            ],
            'title' => [
                'wrapper' => 'mb-2 flex items-center justify-between',
                'title' => 'dark:text-dark-400 block text-sm font-semibold text-gray-600',
                'bar' => 'flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500',
                'progress' => 'flex w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700',
                'percent' => 'dark:text-dark-400 text-sm font-medium text-gray-600',
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
