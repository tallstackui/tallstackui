<?php

namespace TallStackUi\Components\Dial\Items;

use Illuminate\Contracts\View\View;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\DialItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dial.items')]
#[PassThroughRuntime(DialItemsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $href = null,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.dial.items');
    }

    public function customization(): array
    {
        return [
            'item' => 'flex h-10 w-10 items-center justify-center shadow-lg bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-800 dark:bg-dark-700 dark:text-dark-300 dark:hover:bg-dark-600 dark:hover:text-dark-200 focus:outline-hidden cursor-pointer transition-colors',
            'label' => 'absolute whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-xs text-white dark:bg-dark-600',
            'icon' => 'h-5 w-5',
        ];
    }

    protected function validate(): void
    {
        if (! $this->icon) {
            __ts_validation_exception($this, 'The [icon] property is required.');
        }
    }
}
