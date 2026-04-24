<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class AccordionItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var ComponentSlot|string|null $trigger */
        $trigger = $this->data('trigger');

        /** @var string|null $id */
        $id = $this->data('id');

        return [
            'content' => [
                'trigger' => is_string($trigger) ? $trigger : ($trigger?->toHtml() ?? null),
            ],
            'identifier' => $id ?? uniqid('accordion_'),
        ];
    }
}
