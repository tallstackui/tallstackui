<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class DialogColors
{
    use SetupColors;

    public function colors(): array
    {
        [
            $cancel,
            $confirm,
            $icon,
            $background,
            $colorful
        ] = $this->get('cancel', 'confirm', 'icon', 'background', 'colorful');

        return [
            'cancel' => $cancel ?? $this->cancel(),
            'confirm' => array_merge($this->confirm(), array_filter($confirm)),
            'icon' => [
                'background' => array_merge($this->icon()['background'], array_filter($icon['background'])),
                'icon' => array_merge($this->icon()['icon'], array_filter($icon['icon'])),
            ],
            'background' => array_merge($this->background(), array_filter($background)),
            'colorful' => [
                'cancel' => $colorful['cancel'] ?? $this->colorful()['cancel'],
                'confirm' => array_merge($this->colorful()['confirm'], array_filter($colorful['confirm'] ?? [])),
            ],
        ];
    }

    private function background(): array
    {
        return [
            'success' => 'bg-green-500! dark:bg-green-500!',
            'error' => 'bg-red-500! dark:bg-red-500!',
            'info' => 'bg-blue-500! dark:bg-blue-500!',
            'warning' => 'bg-yellow-500! dark:bg-yellow-500!',
            'question' => 'bg-primary-500! dark:bg-primary-500!',
        ];
    }

    private function cancel(): string
    {
        return 'red';
    }

    private function colorful(): array
    {
        return [
            'cancel' => 'bg-transparent text-white/80! hover:bg-white/20 hover:text-white! focus:bg-white/20 focus:text-white! focus:ring-white/50',
            'confirm' => [
                'base' => 'bg-white hover:bg-white/90 focus:ring-white/50',
                'success' => 'text-green-700!',
                'error' => 'text-red-700!',
                'info' => 'text-blue-700!',
                'warning' => 'text-yellow-700!',
                'question' => 'text-primary-700!',
            ],
        ];
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
            'background' => [
                'success' => 'bg-green-100 dark:bg-dark-600',
                'error' => 'bg-red-100 dark:bg-dark-600',
                'info' => 'bg-blue-100 dark:bg-dark-600',
                'warning' => 'bg-yellow-100 dark:bg-dark-600',
                'question' => 'bg-secondary-100 dark:bg-dark-600',
            ],
            'icon' => [
                'success' => 'text-green-600 dark:text-green-500',
                'error' => 'text-red-600 dark:text-red-500',
                'info' => 'text-blue-600 dark:text-blue-500',
                'warning' => 'text-yellow-600 dark:text-yellow-500',
                'question' => 'text-gray-600 dark:text-dark-500',
            ],
        ];
    }
}
