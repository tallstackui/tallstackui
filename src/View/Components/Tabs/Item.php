<?php

namespace TasteUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Item extends Component implements Customizable
{
    public function __construct(
        public ?string $tab = null,
    ) {
        //
    }

    public function render(): View
    {
        return view('taste-ui::components.tabs.item');
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
            'item' => 'bg-white rounded-bl-lg rounded-br-lg rounded-tr-lg dark:bg-gray-600 p-6',
        ];
    }
}
