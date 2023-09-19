<?php

namespace TasteUi\View\Components\Select\Traits;

use TasteUi\View\Components\Select\Searchable;

trait InteractsWithSelectOptions
{
    private function options(): void
    {
        if (! $this->select || (! $this instanceof Searchable && ! is_array($this->options[0]))) {
            return;
        }

        $select = explode('|', $this->select);
        $label = explode(':', $select[0])[1];
        $value = explode(':', $select[1])[1];

        $this->options = collect($this->options)->map(function (array $item) use ($label, $value) {
            return [
                $label => $item[$label],
                $value => $item[$value],
            ];
        })->toArray();

        $this->selectable = [
            'label' => $label,
            'value' => $value,
        ];
    }
}
