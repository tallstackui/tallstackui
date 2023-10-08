<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Dialog extends Component implements Customizable
{
    public function __construct(
        public ?string $zIndex = null,
        public bool $blur = false,
        public bool $closeable = true,
        public bool $square = false,
    ) {
        $this->zIndex ??= config('tallstackui.personalizations.dialog.z-index');
        $this->closeable = config('tallstackui.personalizations.dialog.closeable');
        $this->square = config('tallstackui.personalizations.dialog.square');
    }

    public function render(): View
    {
        return view('tallstack-ui::components.interactions.dialog');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'background' => 'fixed inset-0 bg-gray-400 bg-opacity-75 transition-opacity',
            'wrapper' => [
                'first' => Arr::toCssClasses([
                    'fixed inset-0 z-10 w-screen overflow-y-auto',
                    'backdrop-blur-sm' => $this->blur === true,
                ]),
                'second' => 'flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0',
                'third' => Arr::toCssClasses([
                    'relative w-full max-w-sm transform overflow-hidden bg-white p-4 text-left shadow-xl transition-all sm:my-8',
                    'rounded-lg' => ! $this->square,
                ]),
            ],
            'icon' => [
                'wrapper' => 'mx-auto flex h-12 w-12 items-center justify-center rounded-full',
                'size' => 'h-8 w-8',
            ],
            'text' => [
                'wrapper' => 'mt-3 text-center sm:mt-5',
                'title' => 'text-lg font-semibold leading-6 text-gray-700',
                'description' => [
                    'wrapper' => 'mt-2',
                    'text' => 'text-sm text-gray-500',
                ],
            ],
            'buttons' => [
                'wrapper' => 'mt-5 space-y-2 sm:space-x-2 sm:space-y-0 sm:mt-6 sm:flex sm:justify-end',
                'cancel' => Arr::toCssClasses([
                    'inline-flex w-full items-center justify-center bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300',
                    'rounded-md' => ! $this->square,
                ]),
                'confirm' => Arr::toCssClasses([
                    'inline-flex w-full items-center justify-center px-4 py-2 text-sm transition font-semibold text-white outline-none ease-in group focus:ring-2 focus:ring-offset-2',
                    'rounded-md' => ! $this->square,
                ]),
                'close' => [
                    'wrapper' => 'flex justify-end',
                    'base' => 'h-5 w-5 cursor-pointer text-gray-400',
                ],
            ],
        ]);
    }
}
