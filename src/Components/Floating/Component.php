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
        public ?ComponentSlot $transition = null,
        public ?ComponentSlot $footer = null,
    ) {
        //
    }

    final public function anchor(): string
    {
        return match ($this->offset !== null) {
            true => "x-anchor.{$this->position}.offset.{$this->offset}",
            default => "x-anchor.{$this->position}",
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
}
