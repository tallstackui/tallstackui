<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('table')]
class Table extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $id = null,
        public Collection|array $headers = [],
        public LengthAwarePaginator|Paginator|Collection|array $rows = [],
        public ?bool $headerless = false,
        public ?bool $striped = false,
        public ?array $sort = [],
        public bool|array|null $filter = null,
        public ?bool $loading = false,
        public ?array $quantity = [10, 25, 50, 100],
        #[SkipDebug]
        public ?array $placeholders = [],
        public ?bool $paginate = false,
        public ?bool $persistent = false,
        #[SkipDebug]
        public ?string $paginator = 'tallstack-ui::components.table.paginators',
        public ?bool $simplePagination = false,
        #[SkipDebug]
        public mixed $loop = null,
        #[SkipDebug]
        public array|string $target = [],
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->placeholders = __('tallstack-ui::messages.table');

        if (is_bool($filter) && $this->filter === true) {
            $this->filter = ['quantity' => 'quantity', 'search' => 'search'];
        } else {
            $this->filter = is_array($filter) ? $filter : null;
        }

        // This is necessary to `wire:target` the properties linked with filter
        // in order to make the spinner displayed during Livewire updates.
        if ($quantity = ($this->filter['quantity'] ?? null)) {
            $this->target[] = $quantity;
        }

        if ($search = ($this->filter['search'] ?? null)) {
            $this->target[] = $search;
        }

        // Imploding to transform into "wire:target="quantity,search""
        $this->target = implode(',', $this->target);

        if ($this->id !== null) {
            $this->id = str($this->id)->kebab()
                ->lower()
                ->value();
        }
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.table.index');
    }

    public function head(Collection|array $header): array
    {
        if (! $this->sortable($header) || blank($this->sort)) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $this->sort['direction'] === 'asc' ? 'desc' : 'asc';

        return ['column' => $header['index'], 'direction' => $direction];
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'overflow-hidden dark:ring-dark-600 rounded-lg shadow ring-1 ring-gray-300',
            'table' => [
                'wrapper' => 'relative soft-scrollbar overflow-auto',
                'base' => 'dark:divide-dark-500/50 min-w-full divide-y divide-gray-200',
                'sort' => 'ml-2 h-4 w-4',
                'th' => 'dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700',
                'tbody' => 'dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white',
                'td' => 'dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500',
                'thead' => [
                    'normal' => 'bg-gray-50 dark:bg-dark-600',
                    'striped' => 'bg-white dark:bg-dark-700',
                ],
            ],
            'loading' => [
                'table' => 'cursor-not-allowed select-none opacity-25',
                'icon' => 'text-primary-500 dark:text-dark-300 absolute bottom-0 left-0 right-0 top-0 m-auto grid h-10 w-10 animate-spin place-items-center',
            ],
            'empty' => 'dark:text-dark-300 col-span-full whitespace-nowrap px-3 py-4 text-sm text-gray-500',
            'filter' => 'mb-4 flex items-end gap-x-2 sm:gap-x-0',
            'slots' => [
                'header' => 'mb-2 dark:text-dark-300 text-gray-500',
                'footer' => 'mt-2 dark:text-dark-300 text-gray-500',
            ],
        ]);
    }

    final public function sortable(Collection|array $header): bool
    {
        return filled($this->sort) && ($header['sortable'] ?? true);
    }

    final public function sorted(Collection|array $header): bool
    {
        return $this->sortable($header) && $this->sort['column'] === $header['index'];
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $messages = __('tallstack-ui::messages.table');

        if (blank($messages['empty'] ?? null)) {
            throw new InvalidArgumentException('The table [empty] message cannot be empty.');
        }

        if (blank($messages['quantity'] ?? null)) {
            throw new InvalidArgumentException('The table [quantity] message cannot be empty.');
        }

        if (blank($messages['search'] ?? null)) {
            throw new InvalidArgumentException('The table [search] message cannot be empty.');
        }

        if ($this->persistent && blank($this->id)) {
            throw new InvalidArgumentException('The table [id] property is required when [persistent] is set.');
        }
    }
}
