<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('skeleton')]
class Skeleton extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $width = null,
        public ?string $height = null,
        public ?string $size = null,
        public ?string $rounded = null,
        public ?bool $image = false,
        public ?bool $video = false,
        public ?bool $avatar = false,
    ) {
        $this->rounded = match ($rounded) {
            'none' => 'rounded-none',
            'sm' => 'rounded-md',
            'md' => 'rounded-lg',
            'lg' => 'rounded-xl',
            'xl' => 'rounded-2xl',
            '2xl' => 'rounded-3xl',
            'full' => 'rounded-full',
            default => $rounded,
        };

        $this->size = $this->size ? "size-{$this->size}" : null;
        $this->width = $this->width ? "w-{$this->width}" : 'w-full';
        $this->height = $this->height ? "h-{$this->height}" : 'h-5';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.skeleton');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center justify-center bg-gray-200 animate-pulse dark:bg-gray-700',
            'icon' => 'w-10 h-10 text-gray-400 dark:text-gray-600'
        ]);
    }
}
