<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalization;
use TallStackUi\View\Personalizations\SoftPersonalization;

#[SoftPersonalization('tab.items')]
class Items extends Component implements Personalization
{
    public function __construct(public ?string $tab = null)
    {
        //
    }

    public function personalization(): array
    {
        return ['item' => 'dark:text-dark-300 dark:bg-dark-700 bg-white p-6 text-gray-700 rounded-bl-lg rounded-br-lg rounded-tr-lg'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
