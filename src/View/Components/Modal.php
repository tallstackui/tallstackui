<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('modal')]
class Modal extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool|string|null $blur = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public ?string $entangle = 'modal',
        public ?bool $center = null,
        public ?bool $overflow = null,
    ) {
        $this->entangle = is_string($this->wire) ? $this->wire : (is_bool($this->wire) ? 'modal' : $this->entangle);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.modal');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                'second' => 'fixed inset-0 z-50 w-screen overflow-y-auto',
                'third' => 'mx-auto flex min-h-full w-full transform justify-center p-4',
                'fourth' => 'dark:bg-dark-700 relative flex w-full transform flex-col rounded-xl bg-white text-left shadow-xl transition-all',
            ],
            'positions' => [
                'top' => 'items-end sm:items-start',
                'center' => 'items-end sm:items-center',
            ],
            'blur' => [
                'sm' => 'backdrop-blur-sm',
                'md' => 'backdrop-blur-md',
                'lg' => 'backdrop-blur-lg',
                'xl' => 'backdrop-blur-xl',
            ],
            'title' => [
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b border-b-gray-100 px-4 py-2.5',
                'text' => 'text-md text-secondary-600 dark:text-dark-300 whitespace-normal font-medium',
                'close' => 'text-secondary-300 h-5 w-5 cursor-pointer',
            ],
            'body' => 'dark:text-dark-300 grow rounded-b-xl py-5 text-gray-700 px-4',
            'footer' => 'dark:text-dark-300 dark:border-t-dark-600 flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 p-4 text-gray-700',
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (is_string($this->wire) && $this->wire === '') {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = collect(config('tallstackui.settings.modal'));
        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

        if (! in_array($this->size ?? $configuration->get('size', '2xl'), $sizes)) {
            throw new InvalidArgumentException('The modal size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str($this->zIndex ?? $configuration->get('z-index', 'z-50'))->startsWith('z-')) {
            throw new InvalidArgumentException('The modal z-index must start with z- prefix');
        }
    }
}
