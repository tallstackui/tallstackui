<?php

namespace TallStackUi\Components\Slide;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\SlideRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('slide')]
#[PassThroughRuntime(SlideRuntime::class)]
class Component extends TallStackUiComponent implements Customization
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
        return view('ts-ui::components.slide.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
                'second' => 'fixed inset-0 overflow-hidden',
                'third' => 'absolute inset-0 overflow-hidden',
                'fourth' => 'pointer-events-none fixed flex max-w-full',
                'fifth' => 'flex flex-col bg-white py-6 shadow-xl dark:bg-dark-700',
                'panel' => [
                    'inset-y' => 'inset-y-0',
                    'bottom' => 'bottom-0',
                    'left' => 'left-0',
                    'pr-10' => 'pr-10',
                    'right' => 'right-0',
                    'pl-10' => 'pl-10',
                    'h-full' => 'h-full',
                    'w-full-dvw' => 'w-[100dvw]',
                ],
                'inner' => [
                    'horizontal' => 'pointer-events-auto w-screen',
                    'h-full' => 'h-full',
                ],
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
            'footer' => [
                'base' => 'flex border-t border-t-gray-200 px-4 pt-4 dark:border-t-dark-600',
                'start' => 'justify-start',
                'end' => 'justify-end',
            ],
            'header' => [
                'base' => 'px-6',
                'divider' => 'border-b border-b-gray-200 pb-4 dark:border-b-dark-600',
                'layout' => [
                    'base' => 'flex items-start',
                    'with-title' => 'justify-between',
                    'no-title' => 'justify-end',
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            __ts_validation_exception($this, 'The [wire] property cannot be an empty string');
        }

        $configuration = __ts_get_component_configuration(self::class);
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['right', 'left', 'top', 'bottom'];

        if (! in_array($this->size ?? $configuration['size'] ?? 'lg', $sizes)) {
            __ts_validation_exception($this, 'The [size] must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str($this->zIndex ?? $configuration['z-index'] ?? 'z-50')->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }

        if (! in_array($this->left ? 'left' : $configuration['position'] ?? 'right', $positions) && ! in_array($this->top ? 'top' : $configuration['position'] ?? 'right', $positions) && ! in_array($this->bottom ? 'bottom' : $configuration['position'] ?? 'right', $positions)) {
            __ts_validation_exception($this, 'The [position] must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
