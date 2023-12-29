<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

// TODO: different icons when icon
class Clipboard extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $label = null,
        public ?string $text = null,
        public ?bool $textarea = null,
        public ?bool $icon = null,
        public ?bool $left = false,
        #[SkipDebug]
        public ?array $placeholders = [],
    ) {
        // TODO: validate?
        $this->placeholders = __('tallstack-ui::messages.clipboard');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.clipboard');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
