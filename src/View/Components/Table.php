<?php

namespace TallStackUi\View\Components;

use ArrayAccess;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\RequireLivewireContext;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\TableRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftPersonalization('table')]
#[PassThroughRuntime(TableRuntime::class)]
class Table extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public Collection|array $headers = [],
        public LengthAwarePaginator|Paginator|Collection|array $rows = [],
        public ?bool $headerless = false,
        public ?bool $striped = false,
        public ?array $sort = [],
        public bool|array|null $filter = null,
        public ?bool $loading = false,
        public ?array $quantity = [10, 25, 50, 100],
        public ?bool $paginate = false,
        public ?bool $persistent = false,
        public ?bool $simplePagination = false,
        public ?bool $selectable = null,
        public ?string $selectableProperty = 'id',
        public ?string $link = null,
        public ?bool $blank = false,
        public ?int $onEachSide = 1,
        #[SkipDebug]
        public ?array $placeholders = null,
        #[SkipDebug]
        public ?string $paginator = 'tallstack-ui::components.table.paginators',
        #[SkipDebug]
        public mixed $loop = null,
        #[SkipDebug]
        public array|string $target = [],
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null
    ) {
        $this->placeholders = array_merge(trans('tallstack-ui::messages.table'), $this->placeholders ?? []);

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

    // Prepare the href link for the row replacing tokens
    public function href(mixed $row): string
    {
        return str($this->link)->replaceMatches('/\{(.*?)\}/', fn (array $match): ?string => data_get($row, $match[1]))->value();
    }

    public function ids(): array
    {
        return $this->rows instanceof ArrayAccess
            ? $this->rows->pluck($this->selectableProperty)->all()
            : collect($this->rows)->pluck($this->selectableProperty)->all();
    }

    // We need this to be applied to the checkbox corresponding
    // to the line because it is the x-model from here that "pushes"
    // the selected values, as well as removing them, when clicked.
    final public function modifier(): ComponentAttributeBag
    {
        $modifier = is_string($this->ids()[0] ?? null) ? '' : '.number';

        return new ComponentAttributeBag(['x-model'.$modifier => 'model']);
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
                'tr' => '',
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
            'filter' => [
                'wrapper' => 'mb-4 flex items-end gap-x-2 sm:gap-x-0',
                'quantity' => 'w-1/4 sm:w-1/5',
                'search' => 'sm:w-1/5',
            ],
            'slots' => [
                'header' => 'mb-2 dark:text-dark-300 text-gray-500',
                'footer' => 'mt-2 dark:text-dark-300 text-gray-500',
            ],
        ]);
    }

    final public function sortable(Collection|array $header): bool
    {
        return data_get($header, 'index') !== 'action' && filled($this->sort) && ($header['sortable'] ?? true);
    }

    final public function sorted(Collection|array $header): bool
    {
        return $this->sortable($header) && $this->sort['column'] === $header['index'];
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        $messages = trans('tallstack-ui::messages.table');

        if (blank($messages['empty'] ?? null)) {
            __ts_validation_exception($this, 'The [empty] message cannot be empty.');
        }

        if (blank($messages['quantity'] ?? null)) {
            __ts_validation_exception($this, 'The [quantity] message cannot be empty.');
        }

        if (blank($messages['search'] ?? null)) {
            __ts_validation_exception($this, 'The [search] message cannot be empty.');
        }

        if ($this->selectable && blank($this->selectableProperty)) {
            __ts_validation_exception($this, 'The [selectableProperty] property is required when [selectable] is set.');
        }
    }
}
