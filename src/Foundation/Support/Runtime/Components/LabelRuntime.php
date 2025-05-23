<?php

namespace TallStackUi\Foundation\Support\Runtime\Components;

use TallStackUi\Foundation\Support\Runtime\AbstractRuntime;

class LabelRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $text = $this->data('label') ?? $this->data('slot')->toHtml();

        if ($asterisk = str($text)->endsWith(' *')) {
            $text = str($text)->before(' *')->value();
        }

        return ['word' => $text, 'asterisk' => $asterisk];
    }
}
