<?php

namespace TallStackUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Items extends Component implements Customizable
{
    public function __construct(
        public ?string $tab = null,
        public bool $square = false,
    ) {
        $this->square = config('tallstackui.personalizations.tabs.square');
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tabs.items');
    }

    public function tallStackUiClasses(): array
    {
        return [
            'item' => Arr::toCssClasses([
                'bg-white dark:bg-gray-600 p-6',
                'rounded-bl-lg rounded-br-lg rounded-tr-lg' => ! $this->square,
            ]),
        ];
    }
}
