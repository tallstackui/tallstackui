<?php

namespace TallStackUi\Components\Chart;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Fixtures\ChartCombined;
use Tests\Browser\Fixtures\ChartComparison;
use Tests\Browser\Fixtures\ChartSlices;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_hide_a_series_through_the_legend(): void
    {
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(fn (Browser $browser) => Assert::assertSame(2, $this->drawn($browser), 'both series should start visible'))
            ->click('@tallstackui_chart_legend_1')
            ->pause(200)
            ->tap(function (Browser $browser): void {
                Assert::assertSame(1, $this->drawn($browser), 'the toggled series should be hidden');
                Assert::assertStringContainsString('opacity-40', $this->attribute($browser, '[dusk=tallstackui_chart_legend_1]', 'class'));
            })
            ->click('@tallstackui_chart_legend_1')
            ->pause(200)
            ->tap(fn (Browser $browser) => Assert::assertSame(2, $this->drawn($browser), 'clicking again should bring it back'));
    }

    #[Test]
    public function can_hide_a_slice_through_the_legend(): void
    {
        Livewire::visit(new ChartSlices)
            ->waitFor('@tallstackui_chart')
            ->tap(fn (Browser $browser) => Assert::assertSame(4, $this->slices($browser)))
            ->tap(function (Browser $browser): void {
                $before = $this->arc($browser, 1);

                $browser->click('@tallstackui_chart_legend_0');
                $browser->pause(300);

                Assert::assertSame(3, $this->slices($browser), 'the toggled slice should be gone');
                // Unlike a curve, a pie cannot be rescaled by a transform:
                // removing a slice redistributes every remaining angle, so the
                // path of a sibling has to have changed.
                Assert::assertNotSame($before, $this->arc($browser, 1), 'the remaining slices should redistribute');
            })
            ->click('@tallstackui_chart_legend_0')
            ->pause(300)
            ->tap(fn (Browser $browser) => Assert::assertSame(4, $this->slices($browser), 'clicking again should bring it back'));
    }

    #[Test]
    public function can_open_and_close_the_tooltip_by_touch(): void
    {
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(function (Browser $browser): void {
                $browser->script($this->tap(0.5));
                $browser->pause(250);

                Assert::assertStringContainsString('Mar', $this->tooltip($browser), 'a tap should open the tooltip');
            })
            ->tap(function (Browser $browser): void {
                // On touch, pointerleave fires right after the tap. Closing on
                // it would blank the tooltip the instant it appeared.
                $browser->script("document.querySelector('[dusk=tallstackui_chart]').dispatchEvent(new PointerEvent('pointerleave', { pointerType: 'touch', bubbles: true }));");
                $browser->pause(250);

                Assert::assertStringContainsString('Mar', $this->tooltip($browser), 'leaving by touch must not close it');
            })
            ->tap(fn (Browser $browser) => $browser->script("document.body.dispatchEvent(new MouseEvent('click', { bubbles: true }));"))
            ->pause(250)
            ->tap(fn (Browser $browser) => Assert::assertSame('', $this->tooltip($browser), 'tapping outside should close it'));
    }

    #[Test]
    public function can_read_a_combined_chart_from_the_slots(): void
    {
        Livewire::visit(new ChartCombined)
            ->waitFor('@tallstackui_chart')
            ->tap(fn (Browser $browser) => Assert::assertSame(3, $this->drawn($browser), 'the bars and the curve should all be painted'))
            ->tap(fn (Browser $browser) => $browser->script($this->hover(0.4)))
            ->pause(300)
            ->tap(function (Browser $browser): void {
                // Read from the edges the pointer would snap to 33.33 here, so
                // this is what proves the curve and the bars agree on the axis.
                Assert::assertSame('37.5', $this->attribute($browser, '[dusk=tallstackui_chart] line', 'x1'));

                $tooltip = $this->tooltip($browser);

                Assert::assertStringContainsString('09/25', $tooltip);
                Assert::assertStringContainsString('Recorrentes', $tooltip);
                Assert::assertStringContainsString('Total', $tooltip);
            });
    }

    #[Test]
    public function can_show_a_tooltip_tracking_the_pointer(): void
    {
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('', $this->tooltip($browser), 'the tooltip should start hidden');

                $browser->script($this->hover(0.66));
            })
            ->pause(300)
            ->tap(function (Browser $browser): void {
                // Asserting the tooltip's own text rather than the page's:
                // "Mar" is also an x axis label, so assertSee cannot tell the
                // two apart.
                $tooltip = $this->tooltip($browser);

                Assert::assertStringContainsString('Mar', $tooltip);
                Assert::assertStringContainsString('Alpha', $tooltip);
                Assert::assertStringContainsString('25', $tooltip);
                Assert::assertStringContainsString('33', $tooltip);
            })
            ->tap(fn (Browser $browser) => $browser->script("document.querySelector('[dusk=tallstackui_chart]').dispatchEvent(new PointerEvent('pointerleave', { pointerType: 'mouse', bubbles: true }));"))
            ->pause(300)
            ->tap(fn (Browser $browser) => Assert::assertSame('', $this->tooltip($browser), 'leaving the plot should hide it again'));
    }

    #[Test]
    public function cannot_hide_every_series(): void
    {
        // An empty plot has no domain to rescale against, so the last visible
        // series has to stay on screen.
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->click('@tallstackui_chart_legend_0')
            ->pause(200)
            ->click('@tallstackui_chart_legend_1')
            ->pause(200)
            ->tap(fn (Browser $browser) => Assert::assertSame(1, $this->drawn($browser), 'the last series must stay visible'));
    }

    #[Test]
    public function keeps_the_tooltip_inside_the_plot_at_both_edges(): void
    {
        // A percentage clamp cannot know how wide the tooltip is, so half of
        // it used to hang outside the card on the first and last index.
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(function (Browser $browser): void {
                foreach ([0.0, 0.02, 0.5, 0.98, 1.0] as $ratio) {
                    $browser->script($this->hover($ratio));
                    $browser->pause(120);

                    [$overflowLeft, $overflowRight] = $this->overflow($browser);

                    Assert::assertLessThanOrEqual(1, $overflowLeft, "tooltip escapes on the left at {$ratio}");
                    Assert::assertLessThanOrEqual(1, $overflowRight, "tooltip escapes on the right at {$ratio}");
                }
            });
    }

    #[Test]
    public function rescales_the_remaining_series_with_a_transform(): void
    {
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(fn (Browser $browser) => Assert::assertSame('', $this->transform($browser, 1), 'nothing to rescale while everything is visible'))
            ->click('@tallstackui_chart_legend_0')
            ->pause(200)
            ->tap(function (Browser $browser): void {
                // Rescaling a domain is an affine map in y, and Beziers are
                // affine invariant, so the remaining curve is transformed
                // rather than recomputed. Anything else means the curve math
                // leaked into JavaScript.
                Assert::assertMatchesRegularExpression(
                    '/^translate\(0 -?[\d.]+\) scale\(1 [\d.]+\)$/',
                    $this->transform($browser, 1)
                );
            });
    }

    #[Test]
    public function works_inside_a_lazy_livewire_component(): void
    {
        // The failure mode this guards is the one charting libraries hit: the
        // element has no size until the placeholder is swapped out, so
        // anything measuring on init reads zero and never recovers.
        Livewire::component('lazy-chart', ChartComparison::class);

        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <livewire:lazy-chart lazy />
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_chart', 10)
            ->tap(fn (Browser $browser) => Assert::assertSame(2, $this->drawn($browser), 'the chart should render once the placeholder is replaced'))
            ->tap(fn (Browser $browser) => $browser->script($this->hover(0.66)))
            ->pause(300)
            // Hit-testing measures the element on the event, never on init,
            // which is what makes it survive being swapped in late.
            ->assertSee('Mar');
    }

    /** The path of a slice, which changes whenever the circle is redivided. */
    private function arc(Browser $browser, int $index): string
    {
        return (string) $browser->script(
            "return [...document.querySelectorAll('[dusk=tallstackui_chart] > path')][{$index}]?.getAttribute('d') ?? '';"
        )[0];
    }

    private function attribute(Browser $browser, string $selector, string $attribute): string
    {
        return (string) $browser->script("return document.querySelector('{$selector}').getAttribute('{$attribute}');")[0];
    }

    /** How many series groups are actually painted right now. */
    private function drawn(Browser $browser): int
    {
        return (int) $browser->script(
            "return [...document.querySelectorAll('[dusk=tallstackui_chart] > g')].filter((group) => group.style.display !== 'none').length;"
        )[0];
    }

    private function hover(float $ratio): string
    {
        // Pointer events rather than mouse ones, which is what also makes the
        // chart reachable from touch.
        return <<<JS
        const plot = document.querySelector('[dusk=tallstackui_chart]');
        const rect = plot.getBoundingClientRect();

        plot.dispatchEvent(new PointerEvent('pointermove', {
            clientX: rect.left + rect.width * {$ratio},
            clientY: rect.top + rect.height / 2,
            pointerType: 'mouse',
            bubbles: true,
        }));
        JS;
    }

    /** How many pixels the tooltip spills past each edge of the plot. */
    private function overflow(Browser $browser): array
    {
        return $browser->script(
            "const plot = document.querySelector('[dusk=tallstackui_chart]').getBoundingClientRect();
             const tip = document.querySelector('[dusk=tallstackui_chart_tooltip]').getBoundingClientRect();
             return [plot.left - tip.left, tip.right - plot.right];"
        )[0];
    }

    /** How many slices are actually painted right now. */
    private function slices(Browser $browser): int
    {
        return (int) $browser->script(
            "return [...document.querySelectorAll('[dusk=tallstackui_chart] > path')].filter((path) => path.style.display !== 'none').length;"
        )[0];
    }

    private function tap(float $ratio): string
    {
        return <<<JS
        const plot = document.querySelector('[dusk=tallstackui_chart]');
        const rect = plot.getBoundingClientRect();

        plot.dispatchEvent(new PointerEvent('pointerdown', {
            clientX: rect.left + rect.width * {$ratio},
            clientY: rect.top + rect.height / 2,
            pointerType: 'touch',
            bubbles: true,
        }));
        JS;
    }

    /** The tooltip's own text, empty while it is hidden. */
    private function tooltip(Browser $browser): string
    {
        // Hidden through visibility rather than display, so that the position
        // getter can measure its width and keep it inside the plot.
        return (string) $browser->script(
            "const node = document.querySelector('[dusk=tallstackui_chart_tooltip]');
             return node && getComputedStyle(node).visibility !== 'hidden' ? node.innerText : '';"
        )[0];
    }

    private function transform(Browser $browser, int $index = 0): string
    {
        return (string) $browser->script(
            "return [...document.querySelectorAll('[dusk=tallstackui_chart] > g')][{$index}]?.getAttribute('transform') ?? '';"
        )[0];
    }
}
