<?php

namespace TallStackUi\View\Components;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

class Table extends BaseComponent implements Personalization
{
    public function __construct(
        public Collection|array $headers = [],
        public LengthAwarePaginator|Paginator|array $rows = [],
        public ?bool $headerless = false,
        public ?bool $striped = false,
        public ?array $sort = [],
        public ?bool $filter = false,
        public ?bool $loading = false,
        public ?array $quantity = [10, 25, 50, 100],
        public ?array $filters = ['quantity' => 'quantity', 'search' => 'search'],
        #[SkipDebug]
        public ?array $placeholders = [],
        public ?bool $paginate = false,
        #[SkipDebug]
        public ?string $paginator = 'tallstack-ui::components.table.paginators',
        #[SkipDebug]
        public array|string $target = [],
    ) {
        $this->placeholders = __('tallstack-ui::messages.table');

        if ($quantity = ($this->filters['quantity'] ?? null)) {
            $this->target[] = $quantity;
        }

        if ($search = ($this->filters['search'] ?? null)) {
            $this->target[] = $search;
        }

        $this->target = implode(',', $this->target);

        $this->filters['quantity'] ??= null;
        $this->filters['search'] ??= null;
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
        return Arr::dot([]);
    }

    public function sortable(Collection|array $header): bool
    {
        return filled($this->sort) && ($header['sortable'] ?? true);
    }

    public function sorted(Collection|array $header): bool
    {
        return $this->sortable($header) && $this->sort['column'] === $header['index'];
    }

    protected function validate(): void
    {
        $messages = __('tallstack-ui::messages.table');

        if (blank($messages['empty'] ?? null)) {
            throw new Exception('The table [empty] message cannot be empty.');
        }

        if (blank($messages['quantity'] ?? null)) {
            throw new Exception('The table [quantity] message cannot be empty.');
        }

        if (blank($messages['search'] ?? null)) {
            throw new Exception('The table [search] message cannot be empty.');
        }
    }
}
