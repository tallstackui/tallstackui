<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Modal extends Component implements Customizable
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

    public function customize(bool $error = false): array
    {
        return [
            'wrapper' => [...$this->customWrapperClasses()],
            'title' => [
                'wrapper' => $this->customTitleWrapperClasses(),
                'base' => $this->customTitleBaseClasses(),
            ],
            'body' => $this->customBodyClasses(),
            'footer' => $this->customFooterClasses(),
        ];
    }

    public function customWrapperClasses(): array
    {
        return [
            'first' => Arr::toCssClasses([
                'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                'backdrop-blur-sm' => $this->blur === true,
            ]),
            'second' => Arr::toCssClasses([
                'w-full min-h-full transform flex items-end justify-center mx-auto sm:items-start p-4',
                $this->size(),
            ]),
            'third' => Arr::toCssClasses([
                'relative flex w-full transform flex-col overflow-auto rounded-lg bg-white text-left shadow-xl transition-all',
                $this->size(),
            ]),
        ];
    }

    public function customTitleBaseClasses(): string
    {
        return 'whitespace-normal font-medium text-md text-secondary-600';
    }

    public function customTitleWrapperClasses(): string
    {
        return 'flex items-center justify-between border-b px-4 py-2.5 dark:border-0';
    }

    public function customBodyClasses(): string
    {
        return 'px-2 py-5 md:px-4 text-secondary-700 rounded-b-xl grow dark:text-secondary-400';
    }

    public function customFooterClasses(): string
    {
        return 'border-t border-t-gray-100 bg-gray-50 px-6 py-3';
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
