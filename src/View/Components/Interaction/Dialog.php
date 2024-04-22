<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('dialog')]
class Dialog extends BaseComponent implements Personalization
{
    public function blade(): View
    {
        return view('tallstack-ui::components.interaction.dialog');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'background' => 'fixed inset-0 bg-gray-400 bg-opacity-75 transition-opacity',
            'wrapper' => [
                'first' => 'fixed inset-0 z-10 w-screen overflow-y-auto',
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
                'content' => 'text-sm text-gray-500 dark:text-dark-300',
            ],
            'buttons' => [
                'wrapper' => 'mt-4 space-y-2 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3 sm:space-y-0',
                'confirm' => 'group inline-flex w-full items-center justify-center rounded-md px-4 py-2 text-sm font-semibold text-white outline-none transition ease-in focus:ring-2 focus:ring-offset-2',
                'close' => [
                    'wrapper' => 'flex justify-end',
                    'icon' => 'h-5 w-5 cursor-pointer text-gray-400',
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $messages = __('tallstack-ui::messages.dialog.button');

        if (! str(config('tallstackui.settings.dialog.z-index', 'z-50'))->startsWith('z-')) {
            throw new InvalidArgumentException('The dialog z-index must start with z- prefix');
        }

        if (blank($messages['ok'] ?? null)) {
            throw new InvalidArgumentException('The dialog [ok] message cannot be empty.');
        }

        if (blank($messages['confirm'] ?? null)) {
            throw new InvalidArgumentException('The dialog [confirm] message cannot be empty.');
        }

        if (blank($messages['cancel'] ?? null)) {
            throw new InvalidArgumentException('The dialog [cancel] message cannot be empty.');
        }
    }
}
