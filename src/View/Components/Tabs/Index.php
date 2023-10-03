<?php

namespace TasteUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Index extends Component implements Customizable
{
    public function __construct(
        public bool $square = false,
        public array $options = [],
        public ?string $selected = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.tabs.index');
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
            'wrapper' => '-mb-px flex items-stretch overflow-auto',
            'item' => 'inline-flex truncate px-5 py-2.5 text-gray-700 transition cursor-pointer rounded-t-lg',
        ];
    }
}
