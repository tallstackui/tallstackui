<?php

namespace TallStackUi\Foundation\Colors\Classes;

use TallStackUi\Foundation\Colors\Traits\ShareableConstructor;

class ToastColors
{
    use ShareableConstructor;

    public function __invoke(): array
    {
        return ['icon' => $this->get('icon')];
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
}
