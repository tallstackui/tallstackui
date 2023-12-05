<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

#[SoftPersonalization('loading')]
class Loading extends Component implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $text = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $blur = false,
        public ?bool $opacity = true,
    ) {
        $this->configurations();
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 z-50 bg-gray-300',
                'second' => 'flex h-full items-center justify-center',
            ],
            'opacity' => 'bg-opacity-80 dark:bg-opacity-70',
            'blur' => 'backdrop-blur-sm',
            'spinner' => 'z-50 h-12 w-12 animate-spin text-primary-700',
            'text' => 'inline-flex items-center text-lg font-semibold text-primary-500',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.loading');
    }
}
