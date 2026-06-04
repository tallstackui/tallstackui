<?php

namespace TallStackUi\Support\Runtime\Components;

use TallStackUi\Support\Runtime\AbstractRuntime;

class DialRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $horizontal = $this->data('horizontal');
        $position = $this->data('position');

        // The translation offset follows the direction the items expand toward,
        // so the open animation slides from the button: vertical dials rise or
        // drop, horizontal dials slide left or right.
        $translate = match (true) {
            $horizontal && str_contains($position, 'right') => 'translate-x-3',
            $horizontal && str_contains($position, 'left') => '-translate-x-3',
            str_contains($position, 'bottom') => 'translate-y-3',
            default => '-translate-y-3',
        };

        $resting = str_contains($translate, 'translate-x') ? 'translate-x-0' : 'translate-y-0';

        return [
            'anchor' => match (true) {
                $horizontal && str_contains($position, 'right') => 'left',
                $horizontal && str_contains($position, 'left') => 'right',
                str_contains($position, 'bottom') => 'top',
                default => 'bottom',
            },
            'transition' => [
                'start' => 'opacity-0 '.$translate,
                'end' => 'opacity-100 '.$resting,
            ],
        ];
    }
}
