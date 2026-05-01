<?php

namespace TallStackUi\Components\List\Items;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('list.items')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $name = null,
        public ?string $caption = null,
        #[SkipDebug]
        public ComponentSlot|string|null $menu = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.list.items');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center gap-x-4 px-3 py-2 [content-visibility:auto] [contain-intrinsic-size:auto_2.5rem]',
            'content' => [
                'wrapper' => 'flex grow items-center justify-between',
                'inner' => 'flex flex-row items-center gap-2',
            ],
            'name' => 'text-sm font-medium text-secondary-700 dark:text-dark-100',
            'caption' => 'text-xs text-secondary-500 dark:text-dark-400',
            'menu' => [
                'wrapper' => 'shrink-0',
                'trigger' => 'dark:text-dark-400 dark:hover:bg-dark-700 dark:hover:text-dark-200 flex cursor-pointer items-center justify-center rounded-md p-1 text-secondary-400 hover:bg-secondary-100 hover:text-secondary-600 focus:outline-none',
                'icon' => 'size-5',
                'floating' => 'dark:bg-dark-700 dark:border-dark-600 absolute z-40 w-44 overflow-hidden rounded-md border border-secondary-200 bg-white',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (! is_string($this->name) || trim($this->name) === '') {
            __ts_validation_exception($this, 'The [name] attribute is required.');
        }
    }
}
