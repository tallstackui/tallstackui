<?php

namespace Tests\Browser\Fixtures;

use Livewire\Component;

/**
 * Shared fixture for the browser tests. It lives as a real class rather than
 * an anonymous one so it can also be registered as a Livewire component and
 * mounted lazily.
 *
 * @internal
 */
class ChartComparison extends Component
{
    public array $labels = ['Jan', 'Fev', 'Mar', 'Abr'];

    public array $series = [
        ['name' => 'Alpha', 'data' => [10, 40, 25, 60]],
        ['name' => 'Beta', 'data' => [8, 30, 33, 41]],
    ];

    public function render(): string
    {
        return <<<'HTML'
        <div class="p-10">
            <x-chart :$series :$labels height="200" legend tooltip />
        </div>
        HTML;
    }
}
