<?php

namespace TallStackUi\View\Components\Tab;

use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use TallStackUi\View\Personalizations\SoftPersonalization;
use TallStackUi\View\Personalizations\Contracts\Personalization;

#[SoftPersonalization('tab.items')]
class Items extends Component implements Personalization
{
    public function __construct(
        public ?string $tab = null,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex items-center gap-2 p-4 whitespace-nowrap',
            'unselect' => 'hidden cursor-pointer hover:text-primary-500 sm:flex',
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.tab.items');
    }
}
