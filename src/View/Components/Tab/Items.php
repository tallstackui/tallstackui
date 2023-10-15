<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Items extends Component implements Personalize
{
    public function __construct(
        public ?string $tab = null,
        public bool $square = false,
    ) {
        $this->square = config('tallstackui.personalizations.tabs.square');
    }

    public function personalization(): array
    {
        return ['item' => 'bg-white p-6'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
