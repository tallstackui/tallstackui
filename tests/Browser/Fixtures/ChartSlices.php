<?php

namespace Tests\Browser\Fixtures;

use Livewire\Component;

/**
 * Radial fixture for the browser tests.
 *
 * @internal
 */
class ChartSlices extends Component
{
    public array $labels = ['Direto', 'Busca', 'Social', 'E-mail'];

    public array $series = [45, 25, 18, 12];

    public function render(): string
    {
        return <<<'HTML'
        <div class="p-10">
            <x-chart :$series :$labels type="donut" height="240" legend tooltip />
        </div>
        HTML;
    }
}
