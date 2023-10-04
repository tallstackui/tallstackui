<?php

namespace TasteUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;

class Item extends Component implements Customizable
{
    public function __construct(
        public ?string $tab = null,
        private bool $square = false,
    ) {
        $this->square = config('tasteui.personalizations.tabs.square');
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
            'item' => Arr::toCssClasses([
                'bg-white dark:bg-gray-600 p-6',
                'rounded-bl-lg rounded-br-lg rounded-tr-lg' => ! $this->square,
            ]),
        ];
    }
}
