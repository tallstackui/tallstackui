<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Interaction\Dialog;

class DialogColors
{
    use OverrideColors;

    public function __construct(protected Dialog $component)
    {
        $this->setup();
    }

    public function __invoke(): array
    {
        [$cancel, $confirm, $background, $icon] = $this->get('cancel', 'confirm', 'background', 'icon');

        return [
            'cancel' => $cancel,
            'confirm' => $confirm,
            'background' => $background,
            'icon' => $icon,
        ];
    }

    private function background(): array
    {
        return [
            'success' => 'bg-green-100 dark:bg-dark-600',
            'error' => 'bg-red-100 dark:bg-dark-600',
            'info' => 'bg-blue-100 dark:bg-dark-600',
            'warning' => 'bg-yellow-100 dark:bg-dark-600',
            'question' => 'bg-secondary-100 dark:bg-dark-600',
        ];
    }

    private function cancel(): string
    {
        return 'red';
    }

    private function confirm(): array
    {
        return [
            'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-100 dark:ring-offset-green-900',
            'error' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-100 dark:ring-offset-red-900',
            'info' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-100 dark:ring-offset-blue-900',
            'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-100 dark:ring-offset-yellow-900',
            'question' => 'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 focus:ring-offset-primary-100 dark:ring-offset-primary-900',
        ];
    }

    private function icon(): array
    {
        return [
            'success' => 'text-green-600 dark:text-green-500',
            'error' => 'text-red-600 dark:text-red-500',
            'info' => 'text-blue-600 dark:text-blue-500',
            'warning' => 'text-yellow-600 dark:text-yellow-500',
            'question' => 'text-secondary-600 dark:text-secondary-500',
        ];
    }
}
