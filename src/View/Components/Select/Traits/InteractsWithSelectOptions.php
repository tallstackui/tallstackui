<?php

namespace TallStackUi\View\Components\Select\Traits;

use Illuminate\Support\Collection;

trait InteractsWithSelectOptions
{
    private function options(): void
    {
        $this->options = $this->options instanceof Collection
            ? $this->options->toArray()
            : $this->options;

        if (! $this->select || ($this->options !== [] && ! is_array($this->options[0]))) {
            return;
        }

        $select = explode('|', $this->select);
        $label = explode(':', $select[0])[1];
        $value = explode(':', $select[1])[1];

        $this->options = collect($this->options)->map(fn (array $item) => [
            $label => $item[$label],
            $value => $item[$value],
            'disabled' => $item['disabled'] ?? false,
            'description' => current(array_intersect_key($item, array_flip(['description', 'note']))) ?: null,
            'image' => current(array_intersect_key($item, array_flip(['image', 'img', 'img_src']))) ?: null,
        ])->toArray();

        $this->selectable = [
            'label' => $label,
            'value' => $value,
        ];
    }
}
