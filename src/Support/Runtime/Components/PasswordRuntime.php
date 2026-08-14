<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Runtime\AbstractRuntime;

class PasswordRuntime extends AbstractRuntime
{
    /** @throws Exception */
    public function runtime(): array
    {
        $generator = $this->data('generator');

        return [
            ...$this->bind(),
            ...$this->locks(),
            'value' => $this->sanitize(),
            'target' => is_string($generator) ? $generator : null,
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
