<?php

namespace TallStackUi\Support\Colors\Components;

use TallStackUi\Support\Colors\Concerns\SetupColors;

class ToastColors
{
    use SetupColors;

    public function colors(): array
    {
        [$icon, $text, $background] = $this->get('icon', 'text', 'background');

        return [
            'icon' => array_merge($this->icon(), array_filter($icon)),
            'text' => array_merge($this->text(), array_filter($text)),
            'background' => array_merge($this->background(), array_filter($background)),
        ];
    }

    private function background(): array
    {
        return [
            'success' => 'bg-green-500! dark:bg-green-500!',
            'error' => 'bg-red-500! dark:bg-red-500!',
            'info' => 'bg-blue-500! dark:bg-blue-500!',
            'warning' => 'bg-yellow-500! dark:bg-yellow-500!',
            'question' => 'bg-stone-500! dark:bg-stone-500!',
        ];
    }

    private function icon(): array
    {
        return [
            'success' => 'text-green-400',
            'error' => 'text-red-400',
            'info' => 'text-blue-400',
            'warning' => 'text-yellow-400',
            'question' => 'text-secondary-400',
        ];
    }

    private function text(): array
    {
        return [
            'confirm' => 'text-primary-600 dark:text-dark-200',
            'cancel' => 'text-red-700 dark:text-red-500',
        ];
    }
}
