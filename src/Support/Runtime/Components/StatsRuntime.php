<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class StatsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $hasHref = filled($this->data('href'));
        $hasClick = $this->data['attributes']->hasAny(['wire:click', 'wire:click.prevent', 'x-on:click']);
        $number = $this->data('number');
        $chart = $this->data('chart');

        $this->validate($chart);

        return [
            'tag' => $hasHref ? 'a' : 'div',
            'clickable' => $hasHref || $hasClick,
            'animate' => (bool) $this->data('animated') && is_numeric($number),
            'duration' => max(0, (int) ($this->data('duration') ?? 1)),
            // Not "chart": component data is applied after ours and would
            // overwrite a runtime key sharing a prop name.
            'charted' => filled($chart),
        ];
    }

    private function validate(mixed $chart): void
    {
        // Named slots override same-named props in the view data, so only
        // comparing both sources tells a slot from the shorthand array.
        $shorthand = $this->component->chart; // @phpstan-ignore-line

        if ($chart instanceof ComponentSlot && is_array($shorthand) && $shorthand !== []) {
            __ts_validation_exception($this->component, 'Cannot pass both [:chart] and the [chart] slot simultaneously. Choose one.');
        }
    }
}
