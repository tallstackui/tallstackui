<?php

namespace Tests\Browser\Fixtures;

use Livewire\Component;

/**
 * @internal
 */
class ChartCombined extends Component
{
    public array $labels = ['08/25', '09/25', '10/25', '11/25'];

    public array $series = [
        ['name' => 'Novos', 'data' => [8, 10, 12, 14]],
        ['name' => 'Recorrentes', 'data' => [17, 30, 27, 39]],
        ['name' => 'Total', 'data' => [25, 40, 39, 53], 'type' => 'line'],
    ];

    public function render(): string
    {
        return <<<'HTML'
        <div class="p-10">
            <x-chart :$series :$labels type="bar" stacked height="200" legend tooltip />
        </div>
        HTML;
    }
}
