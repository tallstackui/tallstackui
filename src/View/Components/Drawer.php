<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\DrawerRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('drawer')]
#[PassThroughRuntime(DrawerRuntime::class)]
class Drawer extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $id = 'drawer',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool|string|null $blur = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public ?string $position = null,
        public ?string $entangle = 'drawer',
        public ?bool $center = null,
        public ?bool $overflow = null,
    ) {
        $this->entangle = is_string($this->wire) ? $this->wire : (is_bool($this->wire) ? 'drawer' : $this->entangle);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.drawer');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
                'second' => 'fixed inset-0 z-50 w-screen overflow-y-auto',
                'third' => 'mx-auto flex min-h-full w-full transform justify-center p-4',
                'fourth' => 'fixed z-40 w-full transition-transform bg-white shadow-xl dark:bg-gray-800 transform-none bg-gray-400/75 transition-opacity transition-all',
            ],
            'positions' => [
                'left' => 'top-0 left-0 h-screen overflow-y-auto',
                'right' => 'top-0 right-0 h-screen overflow-y-auto',
                'top' => 'top-0 left-0 right-0 w-full min-h-[50%]',
                'bottom' => 'bottom-0 left-0 right-0 w-full min-h-[50%]',
            ],
            'blur' => [
                'sm' => 'backdrop-blur-sm',
                'md' => 'backdrop-blur-md',
                'lg' => 'backdrop-blur-lg',
                'xl' => 'backdrop-blur-xl',
            ],
            'title' => [
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b border-b-gray-100 px-4 py-2.5 mb-4 text-base font-semibold',
                'text' => 'text-md text-secondary-600 dark:text-dark-300 whitespace-normal font-medium',
                'button' => 'text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white',
                'close' => 'w-3 h-3',
            ],
            'body' => 'dark:text-dark-300 grow rounded-b-xl py-5 text-gray-700 px-4',
            'footer' => 'dark:text-dark-300 dark:border-t-dark-600 flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 p-4 text-gray-700',
        ]);
    }
    

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            __ts_validation_exception($this, 'The [wire] property cannot be an empty string');
        }

        $configuration = collect(config('tallstackui.settings.drawer'));
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['bottom','left','right','top'];

        if (! in_array($this->size ?? $configuration->get('size', 'xl'), $sizes)) {
            __ts_validation_exception($this, 'The [size] must be one of the following: ['.implode(', ', $sizes).']');
        }
        if (! in_array($this->position ?? $configuration->get('position', 'right'), $positions)) {
            __ts_validation_exception($this, 'The [position] must be one of the following: ['.implode(', ', $positions).']');
        }
        if (! str($this->zIndex ?? $configuration->get('z-index', 'z-50'))->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }
    }
}
