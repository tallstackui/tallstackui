<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentSlot;
use Illuminate\View\Factory;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

use function Livewire\invade;

class Table extends BaseComponent implements Personalization
{
    public function __construct(
        public Collection|null|array $headers = [],
        public LengthAwarePaginator|Paginator|array $rows = [],
        public ?bool $withoutHeaders = false,
        public ?bool $striped = false,
        public ?array $sort = [],
        public ?bool $filters = false,
        public ?array $quantities = [10, 25, 50, 100],
        public ?string $quantityPropertyBind = 'quantity',
        public ?string $searchPropertyBind = 'search',
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.table.index');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    public function slots(Factory $factory): ?array
    {
        $slots = array_values(array_filter(invade($factory)->slots))[0] ?? null;

        if (! $slots) {
            return null;
        }

        return collect($slots)
            ->mapWithKeys(function (ComponentSlot $slot, string $key) {
                return [$key => $slot];
            })
            ->toArray();
    }
}
