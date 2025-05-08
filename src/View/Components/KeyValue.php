<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\TallStackUiComponent;

class KeyValue extends TallStackUiComponent
{
    public function __construct(
        public ?int $limit = null,
        public ?bool $static = null,
        public ?bool $removable = null,
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
