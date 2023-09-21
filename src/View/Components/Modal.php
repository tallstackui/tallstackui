<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = 'z-50',
        public bool $wire = false,
        public ?string $entangle = 'modal',
        public ?string $title = null,
        public ?string $footer = null,
        public bool $blur = false,
        public bool $closeable = true,
        public string $size = '2xl',
    ) {
        $this->size = $this->size();
    }

    public function render(): View
    {
        return view('taste-ui::components.modal');
    }

    public function size(): string
    {
        return match ($this->size) {
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
}
