<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('loading')]
class Loading extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $zIndex = null,
        public ?string $text = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $blur = null,
        public ?bool $opacity = true,
        public ?bool $overflow = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.loading');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-300 dark:bg-dark-600',
                'second' => 'flex h-full items-center justify-center',
            ],
            'opacity' => 'bg-opacity-80 dark:bg-opacity-70',
            'blur' => 'backdrop-blur-sm',
            'spinner' => 'h-12 w-12 animate-spin text-primary-700 dark:text-white',
            'text' => 'inline-flex items-center text-lg font-semibold text-primary-500',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! str(config('tallstackui.settings.loading')['z-index'])->startsWith('z-')) {
            throw new InvalidArgumentException('The loading [z-index] must start with z- prefix');
        }
    }
}
