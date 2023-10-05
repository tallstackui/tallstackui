<?php

namespace TasteUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\View\Components\Select\Traits\InteractsWithSelectOptions;

class Styled extends Component implements Customizable
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
        public ?array $after = null,
        public ?array $before = null,
    ) {
        $this->options();
    }

    public function render(): View
    {
        return view('taste-ui::components.select.styled', [
            'placeholder' => __('taste-ui::messages.select.placeholder'),
        ]);
    }

    public function customization(): array
    {
        return [
            ...$this->tasteUiClasses(),
        ];
    }

    public function tasteUiClasses(): array
    {
        return [
            'multiple' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1',
            'icon' => 'h-4 w-4 text-red-500 transition hover:text-red-500',
        ];
    }
}
