<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class TimelineRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        /** @var array|null $items */
        $items = $this->data('items');
        $alternate = (bool) $this->data('alternate');
        $color = (string) $this->data('color');
        $slot = $this->data('slot');

        if ($items !== null && $items !== [] && $slot !== null && trim((string) $slot) !== '') {
            __ts_validation_exception($this->component, 'Cannot pass both [:items] and slot content simultaneously. Choose one.');
        }

        $computed = [];

        if ($items !== null && $items !== []) {
            foreach ($items as $index => $item) {
                $source = is_array($item) ? $item : (array) $item;
                $iteration = $index + 1;

                $computed[] = [
                    'title' => $source['title'] ?? null,
                    'description' => $source['description'] ?? null,
                    'date' => $source['date'] ?? null,
                    'icon' => $source['icon'] ?? null,
                    'color' => $source['color'] ?? $color,
                    'reversed' => $alternate && $iteration % 2 === 0,
                ];
            }
        }

        return [
            'computedItems' => $computed,
        ];
    }
}
