<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class ClipboardRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $data = [
            'sentence' => $text = $this->data('text') ?? $this->data('slot')->toHtml(),
            'hash' => md5($text.uniqid()),
        ];

        $this->validate($text);

        return $data;
    }

    public function validate(?string $text = null): void
    {
        if (! $text) {
            __ts_validation_exception($this->component, 'The [text] cannot be empty. You should specify the text using property or slot.');
        }
    }
}
