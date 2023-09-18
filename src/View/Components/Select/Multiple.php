<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class Multiple extends Styled
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        parent::__construct(
            label: $label,
            hint: $hint,
            options: $options,
            searchable: $searchable,
            select: $select,
            selectable: $selectable,
        );
    }

    public function render(): View
    {
        return view('taste-ui::components.select.multiple', [
            'placeholder' => __('taste-ui::messages.select.placeholder'),
        ]);
    }
}
