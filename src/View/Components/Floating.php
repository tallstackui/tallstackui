<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('floating')]
class Floating extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $offset = '10',
        public ?string $position = 'bottom-end',
        public ?ComponentSlot $transition = null,
        public ?ComponentSlot $base = null,
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
        return view('tallstack-ui::components.floating');
    }

    public function personalization(): array
    {
        return ['wrapper' => 'dark:bg-dark-700 border-dark-200 dark:border-dark-600 absolute z-40 rounded-lg border bg-white'];
    }
}
