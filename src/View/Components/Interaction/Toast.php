<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

#[SoftPersonalization('toast')]
class Toast extends Component implements Personalization
{
    use InteractWithProviders;
    use InteractWithValidations;

    public function __construct()
    {
        $this->configurations();
        $this->validate();
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
                'text' => 'dark:text-dark-300 text-sm font-medium text-gray-800',
                'description' => 'dark:text-dark-400 mt-1 text-sm text-gray-700',
            ],
            'buttons' => [
                'wrapper' => 'mt-3 flex gap-x-3',
                'confirm' => 'text-primary-600 dark:text-dark-200 text-sm font-medium focus:outline-none',
                'cancel' => 'text-red-700 dark:text-red-500 text-sm font-medium focus:outline-none',
                'close' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'class' => 'inline-flex text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
                'side-wrapper' => 'ml-4 flex flex-col justify-between min-h-full',
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

    public function render(): View
    {
        return view('tallstack-ui::components.interaction.toast');
    }
}
