<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

class Table extends BaseComponent implements Personalization
{
    public function __construct(
        public Collection|null|array $headers = [],
        public LengthAwarePaginator|Paginator|array $rows = [],
        public ?bool $withoutHeaders = false,
        public ?bool $striped = false,
        public ?array $sort = [],
        public ?bool $filters = false,
        public ?bool $loading = false,
        public ?array $quantities = [10, 25, 50, 100],
        public ?string $quantityPropertyBind = 'quantity',
        public ?string $searchPropertyBind = 'search',
        public ?array $placeholders = [],
        public ?bool $paginate = false,
        public ?string $paginator = 'tallstack-ui::components.table.paginators'
    ) {
        /*TODO: validate*/
        $this->placeholders = __('tallstack-ui::messages.table');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.table.index');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
