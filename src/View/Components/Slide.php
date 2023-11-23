<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('slide')]
class Slide extends Component implements Personalization
{
    use InteractWithProviders;
    use InteractWithValidations;

    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public ?bool $blur = null,
        public ?bool $left = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public string $entangle = 'slide',
    ) {
        $this->validate();
        $this->configurations();

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'slide');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                'second' => 'fixed inset-0 overflow-hidden',
                'third' => 'absolute inset-0 overflow-hidden',
                'fourth' => 'pointer-events-none fixed inset-y-0 flex max-w-full',
                'fifth' => 'flex h-full flex-col overflow-y-auto bg-white py-6 shadow-xl soft-scrollbar dark:bg-dark-700',
            ],
            'title' => [
                'text' => 'whitespace-normal font-medium text-md text-secondary-600 dark:text-dark-300',
                'close' => 'h-5 w-5 cursor-pointer text-secondary-300',
            ],
            'body' => 'grow rounded-b-xl px-6 py-5 text-gray-700 dark:text-dark-300',
            'footer' => 'flex border-t border-t-gray-200 px-2 pt-6 dark:border-t-dark-600',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.slide');
    }
}
