<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('toast')]
class Toast extends BaseComponent implements Personalization
{
    public function blade(): View
    {
        return view('tallstack-ui::components.interaction.toast');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'pointer-events-none fixed inset-0 flex flex-col items-end justify-end gap-y-2 px-4 py-4',
                'second' => 'flex w-full flex-col items-center space-y-4',
                'third' => 'dark:bg-dark-700 pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'fourth' => 'flex p-4',
            ],
            'icon' => [
                'size' => 'h-6 w-6',
            ],
            'content' => [
                'wrapper' => 'ml-3 w-0 flex-1 pt-0.5',
                'text' => 'dark:text-dark-200 text-sm font-medium text-gray-800',
                'description' => 'dark:text-dark-300 mt-1 text-sm text-gray-700',
            ],
            'buttons' => [
                'wrapper' => [
                    'first' => 'mt-2 flex',
                    'second' => 'ml-4 flex min-h-full flex-col justify-between',
                ],
                'confirm' => 'text-primary-600 dark:text-dark-200 text-sm font-medium focus:outline-none',
                'cancel' => 'text-red-700 dark:text-red-500 text-sm font-medium focus:outline-none',
                'close' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'class' => 'inline-flex text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
                'expand' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'class' => 'inline-flex text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
            ],
            'progress' => [
                'wrapper' => 'dark:bg-dark-600 relative h-1 w-full rounded-full bg-neutral-100',
                'bar' => 'bg-primary-500 dark:bg-dark-400 absolute h-full w-24 duration-300 ease-linear',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $configuration = collect(config('tallstackui.settings.toast'));
        $positions = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];
        $messages = __('tallstack-ui::messages.toast.button');

        if (! in_array($configuration->get('position', 'top-right'), $positions)) {
            throw new InvalidArgumentException('The toast position must be one of the following: ['.implode(', ', $positions).']');
        }

        if (! str($configuration->get('z-index', 'z-50'))->startsWith('z-')) {
            throw new InvalidArgumentException('The toast z-index must start with z- prefix');
        }

        if (blank($messages['ok'] ?? null)) {
            throw new InvalidArgumentException('The toast [ok] message cannot be empty.');
        }

        if (blank($messages['confirm'] ?? null)) {
            throw new InvalidArgumentException('The toast [confirm] message cannot be empty.');
        }

        if (blank($messages['cancel'] ?? null)) {
            throw new InvalidArgumentException('The toast [cancel] message cannot be empty.');
        }
    }
}
