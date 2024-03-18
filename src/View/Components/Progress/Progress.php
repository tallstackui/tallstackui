<?php

namespace TallStackUi\View\Components\Progress;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('progress')]
class Progress extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $percent = null,
        public ?string $title = null,
        public ?string $variation = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $simple = false,
        public ?bool $floating = false,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?string $size = null,
        public ?string $color = 'primary',
        public ?string $style = null,
        public ?bool $withText = true,
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
            'sizes' => [
                'xs' => 'h-2.5',
                'sm' => 'h-3',
                'md' => 'h-4',
                'lg' => 'h-5',
            ],
        ]);
    }
}
