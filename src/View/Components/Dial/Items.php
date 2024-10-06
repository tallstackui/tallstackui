<?php

namespace TallStackUi\View\Components\Dial;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('dial.items')]
class Items extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?bool $square = false,
        public ?string $tooltip = null,
        public ?string $tooltipPosition = 'right',
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.dial.items');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'icon' => 'w-5 h-5 mx-auto',
            'button' => 'flex justify-center items-center w-[52px] h-[52px] text-gray-500 hover:text-gray-900 bg-white border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400',
            'label' => 'block mb-px text-xs font-medium',
        ]);
    }
}
