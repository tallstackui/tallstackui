<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;
use TallStackUi\Support\Personalizations\Traits\InteractWithProviders;
use TallStackUi\Support\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('modal')]
class Modal extends Component implements Personalization
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
        public ?bool $persistent = null,
        public ?string $size = null,
        public string $entangle = 'modal',
    ) {
        $this->validate();
        $this->configurations();

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'modal');
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
                'wrapper' => 'dark:border-b-dark-600 flex items-center justify-between border-b border-b-gray-100 px-4 py-2.5',
                'text' => 'text-md text-secondary-600 dark:text-dark-300 whitespace-normal font-medium',
                'close' => 'text-secondary-300 h-5 w-5 cursor-pointer',
            ],
            'body' => 'dark:text-dark-300 grow rounded-b-xl py-5 text-gray-700 px-4',
            'footer' => 'dark:text-dark-300 dark:border-t-dark-600 flex justify-end gap-2 rounded-b-xl border-t border-t-gray-100 p-4 text-gray-700',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.modal');
    }
}
