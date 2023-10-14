<?php

namespace TallStackUi\View\Components\Tabs;

use Illuminate\Contracts\View\View;
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
            'item' => 'bg-white dark:bg-gray-600 p-6',
        ];
    }
}
