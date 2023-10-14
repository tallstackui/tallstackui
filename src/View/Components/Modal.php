<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\Contracts\Customizable;

class Modal extends Component implements Customizable
{
    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = 'z-50',
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool $blur = false,
        public ?bool $uncloseable = false,
        public string $size = '2xl',
        public string $entangle = 'modal',
    ) {
        if (is_string($this->wire) && empty($this->wire)) {
            throw new InvalidArgumentException('The wire property cannot be an empty string.');
        }

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'modal');

        $this->size = match ($this->size) {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            default => 'sm:max-w-2xl',
        };
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.modal');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => Arr::toCssClasses([
                    'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                    'backdrop-blur-sm' => $this->blur === true,
                ]),
                'second' => 'fixed inset-0 z-50 w-screen overflow-y-auto',
                'third' => 'w-full min-h-full transform flex items-end justify-center mx-auto sm:items-start p-4',
                'fourth' => 'relative flex w-full transform flex-col rounded-xl bg-white text-left shadow-xl transition-all',
            ],
            'title' => [
                'wrapper' => 'flex items-center justify-between border-b px-4 py-2.5',
                'text' => 'whitespace-normal font-medium text-md text-secondary-600',
                'close' => 'h-5 w-5 text-secondary-300 cursor-pointer',
            ],
            'body' => 'px-2 py-5 md:px-4 text-gray-700 rounded-b-xl grow dark:text-secondary-400',
            'footer' => 'flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 text-gray-700 bg-gray-50 p-4',
        ]);
    }
}
