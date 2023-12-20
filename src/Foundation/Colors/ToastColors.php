<?php

namespace TallStackUi\Foundation\Colors;

use TallStackUi\Foundation\Colors\Traits\OverrideColors;
use TallStackUi\View\Components\Interaction\Toast;

class ToastColors
{
    use OverrideColors;

    public function __construct(protected Toast $component)
    {
        $this->setup();
    }

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
