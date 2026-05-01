<?php

namespace TallStackUi\Components\List\Main;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('list')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $searchable = false,
        public ?string $searchPlaceholder = null,
        public ?string $height = null,
        public array|Arrayable|null $items = null,
        #[SkipDebug]
        public ComponentSlot|string|null $empty = null,
        #[SkipDebug]
        public array $resolvedItems = [],
    ) {
        $this->resolvedItems = $this->normalize();
    }

    public function blade(): View
    {
        return view('ts-ui::components.list.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'space-y-1.5',
            'box' => 'dark:bg-dark-700 dark:border-dark-600 dark:divide-dark-600 divide-y divide-secondary-200 rounded-md border border-secondary-200 bg-white',
            'search' => [
                'wrapper' => 'relative flex h-11 items-center',
                'icon.wrapper' => 'pointer-events-none absolute left-3 flex size-5 items-center justify-center text-secondary-400 dark:text-dark-400',
                'icon.size' => 'size-5',
                'input' => 'h-full w-full border-0 bg-transparent pl-10 pr-3 text-sm text-secondary-700 placeholder:text-secondary-400 focus:outline-none focus:ring-0 dark:text-dark-100 dark:placeholder:text-dark-400',
            ],
            'items' => [
                'wrapper' => 'dark:divide-dark-600 divide-y divide-secondary-200',
                'scroll' => 'soft-scrollbar overflow-y-auto',
                'height' => [
                    '40' => 'max-h-40',
                    '60' => 'max-h-60',
                    '80' => 'max-h-80',
                    '96' => 'max-h-96',
                ],
            ],
            'empty' => [
                'wrapper' => 'flex items-center justify-center px-3 py-6',
                'text' => 'text-sm text-secondary-500 dark:text-dark-400',
            ],
        ]);
    }

    protected function validate(): void
    {
        $allowed = ['40', '60', '80', '96'];

        if ($this->height !== null && ! in_array($this->height, $allowed, true)) {
            __ts_validation_exception($this, 'The [height] must be one of: ['.implode(', ', $allowed).'].');
        }

        foreach ($this->resolvedItems as $index => $item) {
            $name = data_get($item, 'name');

            if (! is_string($name) || trim($name) === '') {
                __ts_validation_exception($this, "The [name] is required for the item at index [{$index}].");
            }
        }
    }

    private function normalize(): array
    {
        if ($this->items === null) {
            return [];
        }

        $items = $this->items instanceof Arrayable ? $this->items->toArray() : $this->items;

        return array_values($items);
    }
}
