<?php

namespace TallStackUi\Components\Stats;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    /**
     * A feature test only proves the class string reached the markup. It
     * cannot prove the matching rule exists in the built stylesheet, and a
     * stale dist paints the chart over the number instead of behind it.
     */
    #[Test]
    public function chart_layer_resolves_behind_the_content(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-stats number="1234" title="Users" :chart="[10, 40, 25, 60, 30, 80]" />
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_stats_chart')
            ->assertSee('1234')
            ->assertSee('Users')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('-10', $this->style($browser, 'zIndex'), 'a stale dist leaves this as [auto], which paints the chart over the content');
                Assert::assertSame('isolate', $this->style($browser, 'isolation', card: true), 'without a stacking context the negative z-index escapes the card');
                Assert::assertSame('relative', $this->style($browser, 'position', card: true));
                Assert::assertSame('hidden', $this->style($browser, 'overflow'), 'the layer clips itself so the card never clips its own slots');
                Assert::assertSame('none', $this->style($browser, 'pointerEvents'));
            });
    }

    #[Test]
    public function chart_survives_the_number_count_up(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-stats number="1234" animated :duration="1" :chart="[10, 40, 25, 60]" />
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_stats_chart')
            ->pause($this->paused(2))
            // The count-up overwrites the whole textContent of the number
            // element, so this only holds while the chart lives outside it.
            ->assertSee('1,234')
            ->assertPresent('@tallstackui_stats_chart')
            ->assertPresent('@tallstackui_chart');
    }

    private function style(Browser $browser, string $property, bool $card = false): string
    {
        $target = $card ? '.parentElement' : '';

        return $browser->script("return getComputedStyle(document.querySelector('[dusk=tallstackui_stats_chart]'){$target}).{$property};")[0];
    }
}
