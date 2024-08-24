<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class PasswordRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        return [
            ...$this->bind(),
            'icon' => [
                'x-circle' => TallStackUi::icon('x-circle'),
                'check-circle' => TallStackUi::icon('check-circle'),
            ],
            'password' => $this->data['rules']->isNotEmpty() ? [
                'x-on:click' => 'rules = true',
                'x-model.debounce' => 'input',
            ] : [],
        ];
    }
}
