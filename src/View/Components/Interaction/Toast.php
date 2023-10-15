<?php

namespace TallStackUi\View\Components\Interaction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use TallStackUi\View\Personalizations\Traits\InteractWithValidations;

class Toast extends Component implements Personalize
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
                'third' => 'pointer-events-auto w-full max-w-sm overflow-hidden bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'fourth' => 'flex items-start p-4',
            ],
            'icon' => [
                'size' => 'h-6 w-6',
            ],
            'content' => [
                'wrapper' => 'ml-3 w-0 flex-1 pt-0.5',
                'text' => 'text-sm text-gray-800',
                'description' => 'mt-1 text-sm text-gray-700',
            ],
            'buttons' => [
                'wrapper' => 'mt-3 flex gap-x-3',
                'confirm' => 'bg-white text-sm font-semibold text-primary-600 focus:outline-none',
                'cancel' => 'bg-white text-sm font-medium text-secondary-700 focus:outline-none',
                'close' => [
                    'wrapper' => 'ml-4 flex flex-shrink-0',
                    'class' => 'inline-flex bg-white text-gray-400 focus:outline-none focus:ring-0',
                    'size' => 'h-5 w-5',
                ],
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.interaction.toast');
    }
}
