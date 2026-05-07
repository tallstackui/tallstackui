<?php

namespace TallStackUi\Components\Table;

use ArrayAccess;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\TableRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('table')]
#[PassThroughRuntime(TableRuntime::class)]
class Component extends TallStackUiComponent implements Customization
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
        public ?bool $expandable = false,
        public ?bool $highlight = false,
        public ?string $highlightProperty = 'highlight',
        public ?string $link = null,
        public ?bool $blank = false,
        public ?int $onEachSide = 1,
        #[SkipDebug]
        public ?array $placeholders = null,
        #[SkipDebug]
        public ?string $paginator = 'ts-ui::components.table.paginators',
        #[SkipDebug]
        public mixed $loop = null,
        #[SkipDebug]
        public array|string $target = [],
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null,
        #[SkipDebug]
        public ComponentSlot|string|null $empty = null
    ) {
        $this->placeholders = array_merge(trans('ts-ui::messages.table'), $this->placeholders ?? []);

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
        return view('ts-ui::components.table.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'overflow-hidden dark:ring-dark-600 rounded-lg shadow ring-1 ring-gray-300',
            'table' => [
                'wrapper' => 'relative soft-scrollbar overflow-auto',
                'base' => 'dark:divide-dark-500/50 min-w-full divide-y divide-gray-200',
                'sort' => 'ml-2 h-4 w-4',
                'th' => 'dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700',
                'th-uppercase' => 'uppercase',
                'th-checkbox-width' => 'w-8',
                'th-actions-width' => 'w-6',
                'th-sort-wrapper' => 'inline-flex truncate',
                'tbody' => 'dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white',
                'td' => 'dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500',
                'tr' => '',
                'thead' => [
                    'normal' => 'bg-gray-50 dark:bg-dark-600',
                    'striped' => 'bg-white dark:bg-dark-700',
                ],
            ],
            'row' => [
                'striped' => 'bg-gray-50 dark:bg-dark-800/50',
            ],
            'loading' => [
                'table' => 'cursor-not-allowed select-none opacity-25',
                'icon' => 'text-primary-500 dark:text-dark-300 absolute bottom-0 left-0 right-0 top-0 m-auto grid h-10 w-10 animate-spin place-items-center',
            ],
            'empty' => 'dark:text-dark-300 col-span-full whitespace-nowrap px-3 py-4 text-sm text-gray-500',
            'filter' => [
                'wrapper' => 'mb-4 flex items-end gap-x-2 sm:gap-x-0',
                'wrapper-with-search-and-quantity' => 'justify-between',
                'wrapper-quantity-only' => 'justify-start',
                'wrapper-search-only' => 'justify-end',
                'quantity' => 'w-1/4 sm:w-1/5',
                'search' => 'sm:w-1/5',
            ],
            'slots' => [
                'header' => 'mb-2 dark:text-dark-300 text-gray-500',
                'footer' => 'mt-2 dark:text-dark-300 text-gray-500',
            ],
            'expandable' => [
                'wrapper' => 'bg-gray-50 dark:bg-dark-800',
                'button' => 'text-gray-500 dark:text-dark-300 hover:text-gray-700 dark:hover:text-dark-100 transition-transform duration-200',
                'icon' => 'h-4 w-4 transition-transform duration-200',
                'rotated' => 'rotate-90',
                'content' => 'px-4 py-3',
            ],
            'cell-clickable' => 'cursor-pointer',
        ]);
    }

    public function head(Collection|array $header): array
    {
        if (! $this->sortable($header) || blank($this->sort)) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $this->sort['direction'] === 'asc' ? 'desc' : 'asc';

        return ['column' => $header['index'], 'direction' => $direction];
    }

    final public function highlighted(mixed $row): ?string
    {
        if (! $this->highlight) {
            return null;
        }

        $color = data_get($row, $this->highlightProperty);

        if (blank($color)) {
            return null;
        }

        return match ($color) {
            'primary' => 'bg-primary-100 dark:bg-primary-900/20',
            'secondary' => 'bg-secondary-100 dark:bg-secondary-900/20',
            'black' => 'bg-gray-200 dark:bg-gray-800/40',
            'white' => 'bg-white dark:bg-dark-600',
            'slate' => 'bg-slate-100 dark:bg-slate-900/20',
            'gray' => 'bg-gray-100 dark:bg-gray-900/20',
            'zinc' => 'bg-zinc-100 dark:bg-zinc-900/20',
            'neutral' => 'bg-neutral-100 dark:bg-neutral-900/20',
            'stone' => 'bg-stone-100 dark:bg-stone-900/20',
            'red' => 'bg-red-100 dark:bg-red-900/20',
            'orange' => 'bg-orange-100 dark:bg-orange-900/20',
            'amber' => 'bg-amber-100 dark:bg-amber-900/20',
            'yellow' => 'bg-yellow-100 dark:bg-yellow-900/20',
            'lime' => 'bg-lime-100 dark:bg-lime-900/20',
            'green' => 'bg-green-100 dark:bg-green-900/20',
            'emerald' => 'bg-emerald-100 dark:bg-emerald-900/20',
            'teal' => 'bg-teal-100 dark:bg-teal-900/20',
            'cyan' => 'bg-cyan-100 dark:bg-cyan-900/20',
            'sky' => 'bg-sky-100 dark:bg-sky-900/20',
            'blue' => 'bg-blue-100 dark:bg-blue-900/20',
            'indigo' => 'bg-indigo-100 dark:bg-indigo-900/20',
            'violet' => 'bg-violet-100 dark:bg-violet-900/20',
            'purple' => 'bg-purple-100 dark:bg-purple-900/20',
            'fuchsia' => 'bg-fuchsia-100 dark:bg-fuchsia-900/20',
            'pink' => 'bg-pink-100 dark:bg-pink-900/20',
            'rose' => 'bg-rose-100 dark:bg-rose-900/20',
            default => $color
        };
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
        $messages = trans('ts-ui::messages.table');

        if (blank($this->empty) && blank($messages['empty'] ?? null)) {
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

        if ($this->highlight && blank($this->highlightProperty)) {
            __ts_validation_exception($this, 'The [highlightProperty] property is required when [highlight] is set.');
        }
    }
}
