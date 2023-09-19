<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TasteUi\View\Components\Select\Traits\InteractsWithSelectOptions;

class Styled extends Component
{
    use InteractsWithSelectOptions;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?bool $multiple = false,
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        $this->options();
    }

    public function render(): View
    {
        return view('taste-ui::components.select.styled', [
            'placeholder' => __('taste-ui::messages.select.placeholder'),
        ]);
    }
}
