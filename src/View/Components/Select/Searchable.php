<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class Searchable extends Styled
{
    public function __construct(
        public string|array $request,
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $multiple = false,
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        parent::__construct(
            label: $label,
            hint: $hint,
            select: $select,
            selectable: $selectable,
        );
    }

    public function render(): View
    {
        return view('taste-ui::components.select.searchable', [
            'placeholder' => __('taste-ui::messages.select.placeholder'),
        ]);
    }
}
