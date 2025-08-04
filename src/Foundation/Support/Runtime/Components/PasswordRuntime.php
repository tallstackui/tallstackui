<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use Exception;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class PasswordRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        return [
            ...$this->bind(),
            'value' => $this->sanitize(),
            'icon' => [
                'x-circle' => TallStackUi::icon('x-circle'),
                'check-circle' => TallStackUi::icon('check-circle'),
            ],
            'password' => $this->data('simple') ? [
                'x-model.debounce' => 'input',
            ] : [
                'x-on:click' => 'rules = true',
                'x-model.debounce' => 'input',
            ],
        ];
    }
}
