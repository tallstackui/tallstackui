<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('slide')]
class Slide extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $id = 'slide',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool|string|null $blur = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public ?string $entangle = 'slide',
        public ?bool $center = null,
        public ?bool $overflow = null,
        public ?bool $left = null,
    ) {
        $this->entangle = is_string($this->wire) ? $this->wire : (is_bool($this->wire) ? 'slide' : $this->entangle);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.slide');
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
            'blur' => [
                'sm' => 'backdrop-blur-sm',
                'md' => 'backdrop-blur-md',
                'lg' => 'backdrop-blur-lg',
                'xl' => 'backdrop-blur-xl',
            ],
            'title' => [
                'text' => 'whitespace-normal font-medium text-md text-secondary-600 dark:text-dark-300',
                'close' => 'h-5 w-5 cursor-pointer text-secondary-300',
            ],
            'body' => 'grow rounded-b-xl px-6 py-5 text-gray-700 dark:text-dark-300',
            'footer' => 'flex border-t border-t-gray-200 px-4 pt-6 dark:border-t-dark-600',
            'header' => 'px-6',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            throw new InvalidArgumentException('The slide [wire] property cannot be an empty string');
        }

        $configuration = collect(config('tallstackui.settings.slide'));
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['right', 'left'];

        if (! in_array($this->size ?? $configuration->get('size', 'lg'), $sizes)) {
            throw new InvalidArgumentException('The slide size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str($this->zIndex ?? $configuration->get('z-index', 'z-50'))->startsWith('z-')) {
            throw new InvalidArgumentException('The slide [z-index] must start with z- prefix');
        }

        if (! in_array($this->left ? 'left' : $configuration->get('position', 'right'), $positions)) {
            throw new InvalidArgumentException('The slide [position] must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
