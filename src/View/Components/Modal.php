<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

class Modal extends Component implements Personalize
{
    use InteractWithValidations;

    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public bool $blur = false,
        public ?bool $uncloseable = false,
        public ?string $size = null,
        public string $entangle = 'modal',
    ) {
        $this->validate();

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'modal');

        $this->zIndex ??= config('tallstackui.personalizations.modal.z-index');

        $this->size ??= config('tallstackui.personalizations.modal.size', '2xl');

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

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                'second' => 'fixed inset-0 z-50 w-screen overflow-y-auto',
                'third' => 'mx-auto flex min-h-full w-full transform items-end justify-center p-4 sm:items-start',
                'fourth' => 'dark:bg-dark-700 relative flex w-full transform flex-col rounded-xl bg-white text-left shadow-xl transition-all',
            ],
            'blur' => 'backdrop-blur-sm',
            'title' => [
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b px-4 py-2.5',
                'text' => 'text-md text-secondary-600 dark:text-dark-300 whitespace-normal font-medium',
                'close' => 'text-secondary-300 h-5 w-5 cursor-pointer',
            ],
            'body' => 'dark:text-dark-300 grow rounded-b-xl px-2 py-5 text-gray-700 md:px-4',
            'footer' => 'dark:text-dark-300 dark:border-t-dark-600 dark:bg-dark-600 flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 bg-gray-50 p-4 text-gray-700',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.modal');
    }
}
