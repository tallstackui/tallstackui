<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Index extends Component
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $icon = null,
        public ?string $position = null,
        public Collection|array $options = [],
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        $this->options();
    }

    public function render(): View
    {
        return view('taste-ui::components.select.index');
    }

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
