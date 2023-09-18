<?php

namespace TasteUi\View\Components\Select\Traits;

trait InteractsWithSelectOptions
{
    private function options(): void
    {
        if (! $this->select) {
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
