<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\SlideRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('slide')]
#[PassThroughRuntime(SlideRuntime::class)]
class Slide extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $id = 'slide',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ComponentSlot|string|null $title = null,
        public ComponentSlot|string|null $footer = null,
        public bool|string|null $blur = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public ?string $entangle = 'slide',
        public ?bool $center = null,
        public ?bool $overflow = null,
        public ?bool $left = null,
        public ?bool $top = null,
        public ?bool $bottom = null,
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
                'first' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
                'second' => 'fixed inset-0 overflow-hidden',
                'third' => 'absolute inset-0 overflow-hidden',
                'fourth' => 'pointer-events-none fixed flex max-w-full',
                'fifth' => 'flex flex-col bg-white py-6 shadow-xl dark:bg-dark-700',
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
            'body' => 'soft-scrollbar dark:text-dark-300 grow overflow-y-auto rounded-b-xl px-6 py-5 text-gray-700',
            'footer' => 'flex border-t border-t-gray-200 px-4 pt-6 dark:border-t-dark-600',
            'header' => 'px-6',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            __ts_validation_exception($this, 'The [wire] property cannot be an empty string');
        }

        $configuration = collect(config('tallstackui.settings.slide'));
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['right', 'left', 'top', 'bottom'];

        if (! in_array($this->size ?? $configuration->get('size', 'lg'), $sizes)) {
            __ts_validation_exception($this, 'The [size] must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str($this->zIndex ?? $configuration->get('z-index', 'z-50'))->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }

        if (! in_array($this->left ? 'left' : $configuration->get('position', 'right'), $positions) && ! in_array($this->top ? 'top' : $configuration->get('position', 'right'), $positions) && ! in_array($this->bottom ? 'bottom' : $configuration->get('position', 'right'), $positions)) {
            __ts_validation_exception($this, 'The [position] must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
