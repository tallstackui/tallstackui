<?php

namespace TallStackUi\Components\Accordion\Items;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\AccordionItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('accordion.items')]
#[PassThroughRuntime(AccordionItemsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $title = null,
        public ?bool $open = false,
        public ?string $id = null,
        #[SkipDebug]
        public ComponentSlot|string|null $icon = null,
        #[SkipDebug]
        public ComponentSlot|string|null $trigger = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.accordion.items');
    }

    public function customization(): array
    {
        return Arr::dot([
            'item' => [
                'wrapper' => 'border-b border-gray-200 dark:border-dark-600 last:border-b-0',
                'trigger' => [
                    'base' => 'flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-start text-sm font-medium justify-between transition-colors hover:bg-gray-50 dark:hover:bg-dark-600',
                    'open' => 'text-primary-600 dark:text-primary-400',
                    'closed' => 'text-secondary-700 dark:text-dark-300',
                ],
                'content' => 'px-4 pb-4 text-sm text-secondary-600 dark:text-dark-400',
                'icon' => [
                    'base' => 'h-4 w-4 shrink-0 transition-transform duration-200',
                    'rotated' => 'rotate-180',
                ],
            ],
        ]);
    }
}
