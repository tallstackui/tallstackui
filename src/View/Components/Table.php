<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
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
        public Collection|null|array $rows = [],
        public ?bool $withoutHeaders = false,
        public ?bool $striped = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.table');
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
