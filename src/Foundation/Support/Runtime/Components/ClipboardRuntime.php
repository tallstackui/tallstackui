<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use InvalidArgumentException;
use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class ClipboardRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $data = [
            'sentence' => $text = $this->data['text'] ??= $this->data('slot')->toHtml(),
            'hash' => md5($text.uniqid()),
        ];

        $this->validate($text);

        return $data;
    }

    public function validate(?string $text = null): void
    {
        if (! $text) {
            throw new InvalidArgumentException('The clipboard [text] cannot be empty. You should specify the text using property or slot.');
        }
    }
}
