<?php

namespace TallStackUi\Foundation\Components\Colors\Classes;

use TallStackUi\Foundation\Components\Colors\Concerns\ShareableConstructor;

class ToastColors
{
    use ShareableConstructor;

    public function __invoke(): array
    {
        [$icon, $text] = $this->get('icon', 'text');

        return [
            'icon' => array_merge($this->icon(), array_filter($icon)),
            'text' => array_merge($this->text(), array_filter($text)),
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
