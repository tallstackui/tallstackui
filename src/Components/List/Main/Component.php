<?php

namespace TallStackUi\Components\List\Main;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\SkeletonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\ListRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('list')]
#[PassThroughRuntime(ListRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SkeletonSetup;

    public const CHUNK = 20;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $searchable = false,
        public ?string $searchPlaceholder = null,
        public ?bool $compact = false,
        public ?string $height = null,
        public array|Arrayable|null $items = null,
        public bool|int|null $lazy = null,
        public bool|int|null $skeleton = null,
        #[SkipDebug]
        public ComponentSlot|string|null $empty = null,
        #[SkipDebug]
        public array $resolved = [],
    ) {
        if ($this->items !== null) {
            $items = $this->items instanceof Arrayable ? $this->items->toArray() : $this->items;

            $this->resolved = array_values($items);
        }

        $this->lazy = match ($this->lazy) {
            true => self::CHUNK,
            false => null,
            default => $this->lazy,
        };
    }

    public function blade(): View
    {
        return view($this->skeletonized() ? 'ts-ui::components.list.skeleton' : 'ts-ui::components.list.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'space-y-1.5',
            'box' => 'dark:bg-dark-800 dark:border-dark-700 rounded-md border border-gray-200 bg-white',
            'search' => [
                'wrapper' => 'dark:border-dark-700 relative flex h-11 items-center border-b border-gray-200',
                'wrapper-compact' => 'dark:border-dark-600 relative flex h-9 items-center border-b border-gray-200',
                'icon.wrapper' => 'pointer-events-none absolute left-3 flex size-5 items-center justify-center text-gray-400 dark:text-dark-400',
                'icon.size' => 'size-5',
                'input' => 'h-full w-full border-0 bg-transparent pl-10 pr-3 text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-0 dark:text-dark-100 dark:placeholder:text-dark-400',
            ],
            'items' => [
                'wrapper' => '[&>[data-list-on]~[data-list-on]]:border-t [&>[data-list-on]~[data-list-on]]:border-gray-200 dark:[&>[data-list-on]~[data-list-on]]:border-dark-700',
                'scroll' => 'custom-scrollbar overflow-y-auto',
                'height' => [
                    '40' => 'max-h-40',
                    '60' => 'max-h-60',
                    '80' => 'max-h-80',
                    '96' => 'max-h-96',
                ],
            ],
            'empty' => [
                'wrapper' => 'flex items-center justify-center px-3 py-6',
                'wrapper-compact' => 'flex items-center justify-center px-3 py-3',
                'text' => 'text-sm text-gray-500 dark:text-dark-400',
            ],
            'skeleton' => [
                ...$this->blocks(),
                'label' => 'h-3 w-24',
                'hint' => 'h-3 w-40',
                'search' => 'mx-3 h-4 w-1/3',
                'items' => [
                    'wrapper' => 'dark:divide-dark-600 divide-y divide-gray-200',
                    'row' => 'flex items-center justify-between gap-x-2 px-3 py-2.5',
                    'row-compact' => 'flex items-center justify-between gap-x-2 px-3 py-1.5',
                    'content' => 'flex items-center gap-x-2',
                ],
                'name' => 'h-4 w-32',
                'caption' => 'h-3 w-20',
                'menu' => 'size-5 rounded',
            ],
        ]);
    }

    protected function validate(): void
    {
        $this->guard();

        $allowed = ['40', '60', '80', '96'];

        if ($this->height !== null && ! in_array($this->height, $allowed, true)) {
            __ts_validation_exception($this, 'The [height] must be one of: ['.implode(', ', $allowed).'].');
        }

        if ($this->lazy !== null) {
            if ($this->items === null) {
                __ts_validation_exception($this, 'The [lazy] requires the [items] because there is nothing to slice without them.');
            }

            if ($this->height === null) {
                __ts_validation_exception($this, 'The [lazy] requires the [height] to create the scroll container that loads the next rows.');
            }

            if ($this->lazy < 1) {
                __ts_validation_exception($this, 'The [lazy] must be greater than 0.');
            }
        }

        foreach ($this->resolved as $index => $item) {
            $name = data_get($item, 'name');

            if (! is_string($name) || trim($name) === '') {
                __ts_validation_exception($this, "The [name] is required for the item at index [{$index}].");
            }
        }
    }
}
