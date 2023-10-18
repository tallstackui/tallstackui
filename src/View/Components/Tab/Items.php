<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;

class Items extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(public ?string $tab = null)
    {
        $this->configurations();
    }

    public function personalization(): array
    {
        return ['item' => 'dark:text-dark-300 dark:bg-dark-700 bg-white p-6 text-gray-700'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
