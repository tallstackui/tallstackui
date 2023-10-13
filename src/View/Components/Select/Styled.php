<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\Contracts\Customizable;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;

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
        public ?string $after = null,
        public ?string $before = null,
    ) {
        $this->options();

        //TODO: refactor this $this->options[0]

        if (isset($this->options[0]) && (is_array($this->options[0]) && ! $this->select)) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.styled', [
            'placeholder' => __('tallstack-ui::messages.select.placeholder'),
        ]);
    }

    public function tallStackUiClasses(): array
    {
        return [
            'item' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1',
            'icon' => 'h-4 w-4 text-red-500 transition hover:text-red-500',
        ];
    }
}
