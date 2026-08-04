<?php

namespace TallStackUi\Components\Floating;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('floating')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $offset = '10',
        public ?string $position = 'bottom-end',
        public ComponentSlot|string|null $transition = null,
        public ComponentSlot|string|null $footer = null,
    ) {
        //
    }

    final public function anchor(): string
    {
        // Alpine's anchor plugin only knows the 12 concrete placements, so auto*
        // leaves it undefined and Floating UI drops the start/end alignment along
        // with it. Tooltip and Reaction resolve auto* through a different engine.
        $position = str_replace('auto', 'bottom', (string) $this->position);

        return match ($this->offset !== null) {
            true => "x-anchor.{$position}.offset.{$this->offset}",
            default => "x-anchor.{$position}",
        };
    }

    public function blade(): View
    {
        return view('ts-ui::components.floating.main');
    }

    public function customization(): array
    {
        return ['wrapper' => 'dark:bg-dark-700 border-dark-200 dark:border-dark-600 absolute z-50 rounded-lg border bg-white'];
    }

    final public function lockable(): bool
    {
        return (bool) config('ts-ui.floating_scroll_lock', false);
    }
}
