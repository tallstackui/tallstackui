<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Multiple extends Styled
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        parent::__construct(
            id: $id,
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
        return view('taste-ui::components.select.multiple');
    }
}
