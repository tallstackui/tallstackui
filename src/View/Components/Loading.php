<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Traits\InteractWithProviders;

#[SoftPersonalization('loading')]

class Loading extends BaseComponent implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $zIndex = null,
        public ?string $text = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $blur = false,
        public ?bool $opacity = true,
    ) {
        $this->configurations(); //TODO remove this
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

    protected function validate(): void
    {
        $configuration = config('tallstackui.settings.loading');

        if (str_starts_with($configuration['z-index'], 'z-')) {
            return;
        }

        throw new InvalidArgumentException('The loading z-index must start with z- prefix');
    }
}
