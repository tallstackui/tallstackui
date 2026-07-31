<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class ListRuntime extends AbstractRuntime
{
    private const INTERACTIONS = ['item_caption', 'item_action', 'item_menu'];

    public function runtime(): array
    {
        return [
            'lines' => $this->skeleton(4),
            'slice' => $this->slice(),
        ];
    }

    /**
     * Replaces one Blade component per item with a single JSON array,
     * leaving the row rendering to Alpine.
     */
    private function slice(): ?array
    {
        $lazy = $this->data('lazy');

        if ($lazy === null) {
            return null;
        }

        foreach (self::INTERACTIONS as $interaction) {
            if ($this->data($interaction) !== null) {
                __ts_validation_exception($this->component, "The [lazy] cannot be used together with [@interact('{$interaction}')] because the slot is resolved on the server side.");
            }
        }

        return [
            'chunk' => $lazy,
            'items' => array_map(fn (mixed $item): array => [
                'name' => (string) data_get($item, 'name'),
                'caption' => ($caption = data_get($item, 'caption')) === null ? null : (string) $caption,
            ], $this->data('resolved', [])),
        ];
    }
}
