<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\TallStackUiComponent;

class KeyValue extends TallStackUiComponent
{
    public function __construct(
        public ?string $label = null,
        public ?string $value = null,
        public ?int $limit = null,
        public ?bool $static = null,
        public ?bool $removable = null,
        public ComponentSlot|string|null $icon = null,
        #[SkipDebug]
        public ?ComponentSlot $header = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.key-value');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
