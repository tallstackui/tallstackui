<?php

namespace TallStackUi\View\Components\Progress;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('progress.circle')]
class Circle extends BaseComponent implements Personalization
{
    public function __construct(
        public string|int|null $percent = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?string $color = 'primary',
        public ?int $strokeCircle = 2,
        public ?int $strokePercent = 2,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

        $this->style = $this->light ? 'light' : 'solid';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.progress.circle');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'absolute start-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transform',
            'text' => 'text-center font-bold text-gray-800 dark:text-white',
            'background' => 'dark:text-dark-700 text-gray-200',
            'sizes' => [
                'text' => [
                    'xs' => 'text-sm',
                    'sm' => 'text-base',
                    'md' => 'text-xl',
                    'lg' => 'text-2xl',
                ],
                'circle' => [
                    'xs' => 'h-12 w-12',
                    'sm' => 'h-16 w-16',
                    'md' => 'h-28 w-28',
                    'lg' => 'h-36 w-36',
                ],
            ],
        ]);
    }
}
