<?php

namespace TallStackUi\Components\Dialog;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\DialogColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dialog')]
#[ColorsThroughOf(DialogColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function blade(): View
    {
        return view('ts-ui::components.dialog.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'background' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
            'wrapper' => [
                'first' => 'fixed inset-0 z-10 w-screen overflow-y-auto',
                'first-blur' => 'backdrop-blur-sm',
                'second' => 'flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0',
                'third' => 'relative w-full max-w-sm transform overflow-hidden bg-white rounded-xl p-4 text-left shadow-xl transition-all sm:my-8 dark:bg-dark-700',
            ],
            'icon' => [
                'wrapper' => 'mx-auto flex h-12 w-12 items-center justify-center rounded-full',
                'size' => 'h-8 w-8',
            ],
            'text' => [
                'wrapper' => 'mt-3 text-center sm:mt-5',
                'title' => 'text-lg font-semibold leading-6 text-gray-700 dark:text-dark-200',
                'description' => [
                    'wrapper' => 'mt-2',
                    'text' => 'text-sm text-gray-500 dark:text-dark-300',
                ],
            ],
            'buttons' => [
                'wrapper' => 'mt-4 space-y-2 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3 sm:space-y-0',
                'confirm' => 'cursor-pointer group inline-flex w-full items-center justify-center rounded-md px-4 py-2 text-sm font-semibold text-white outline-hidden transition ease-in',
                'confirm-grid' => [
                    'with-cancel' => 'sm:w-auto',
                    'without-cancel' => 'col-span-full',
                ],
                'cancel' => [
                    'base' => 'w-full text-sm focus:ring-0! focus:ring-offset-0!',
                ],
                'close' => [
                    'wrapper' => 'flex justify-end',
                    'icon' => 'h-5 w-5 cursor-pointer text-gray-400',
                ],
            ],
            'colorful' => [
                'icon-wrapper' => 'bg-white/20',
                'icon' => 'text-white',
                'close' => 'h-5 w-5 cursor-pointer text-white',
                'title' => 'text-lg font-semibold leading-6 text-white',
                'description' => 'text-sm text-white/80',
                'confirm' => 'bg-white/20 hover:bg-white/30 text-white font-bold! focus:ring-white/50',
                'cancel' => 'w-full',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $messages = trans('ts-ui::messages.dialog.button');

        if (! str(__ts_get_component_configuration(self::class, 'z-index') ?? 'z-50')->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }

        if (blank($messages['ok'] ?? null)) {
            __ts_validation_exception($this, 'The [ok] message cannot be empty.');
        }

        if (blank($messages['confirm'] ?? null)) {
            __ts_validation_exception($this, 'The [confirm] message cannot be empty.');
        }

        if (blank($messages['cancel'] ?? null)) {
            __ts_validation_exception($this, 'The [cancel] message cannot be empty.');
        }
    }
}
