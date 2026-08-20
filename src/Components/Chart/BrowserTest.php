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
    public function can_rotate_the_axis_labels_instead_of_thinning_them(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $labels = [];

            public array $series = [];

            public function mount(): void
            {
                foreach (range(1, 30) as $day) {
                    $this->labels[] = sprintf('%02d/08', $day);
                    $this->series[] = $day % 7;
                }
            }

            public function render(): string
            {
                return <<<'HTML'
                <div class="p-4">
                    <x-chart :$series :$labels height="200" fit="rotate" />
                </div>
                HTML;
            }
        })
            ->resize(375, 800)
            ->waitFor('@tallstackui_chart')
            ->pause(300)
            ->tap(function (Browser $browser): void {
                $shown = $this->captions($browser);

                // Slanted, a label only takes its line height along the axis,
                // so far more of them fit than upright ones would.
                Assert::assertGreaterThan(10, count($shown), 'rotating should keep most labels');
                Assert::assertStringContainsString('rotate(-45deg)', $this->attribute($browser, '[dusk=tallstackui_chart_caption_0]', 'style'));
                Assert::assertGreaterThan(16, $this->axis($browser), 'the axis should grow to hold the slanted labels');
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
    public function can_stagger_the_axis_labels_on_two_rows(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $labels = [];

            public array $series = [];

            public function mount(): void
            {
                foreach (range(1, 30) as $day) {
                    $this->labels[] = sprintf('%02d/08', $day);
                    $this->series[] = $day % 7;
                }
            }

            public function render(): string
            {
                return <<<'HTML'
                <div class="p-4">
                    <x-chart :$series :$labels height="200" fit="stagger" />
                </div>
                HTML;
            }
        })
            ->resize(375, 800)
            ->waitFor('@tallstackui_chart')
            ->pause(300)
            ->tap(function (Browser $browser): void {
                Assert::assertStringContainsString('top: 14px', $this->attribute($browser, '[dusk=tallstackui_chart_caption_1]', 'style'));
                Assert::assertStringNotContainsString('top:', $this->attribute($browser, '[dusk=tallstackui_chart_caption_0]', 'style'));
                Assert::assertGreaterThan(16, $this->axis($browser), 'the axis should grow to hold two rows');

                foreach ($this->gaps($browser, 0) as $gap) {
                    Assert::assertGreaterThanOrEqual(0, $gap, 'the upper row must not overlap');
                }

                foreach ($this->gaps($browser, 1) as $gap) {
                    Assert::assertGreaterThanOrEqual(0, $gap, 'the lower row must not overlap');
                }
            });
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
    public function hides_the_axis_labels_that_would_overlap(): void
    {
        // Thirty captions on a phone used to pile onto each other into an
        // unreadable strip, because every one of them was always painted.
        Livewire::visit(new class extends Component
        {
            public array $labels = [];

            public array $series = [];

            public function mount(): void
            {
                foreach (range(1, 30) as $day) {
                    $this->labels[] = sprintf('%02d/08', $day);
                    $this->series[] = $day % 7;
                }
            }

            public function render(): string
            {
                return <<<'HTML'
                <div class="p-4">
                    <x-chart :$series :$labels height="200" />
                </div>
                HTML;
            }
        })
            ->resize(375, 800)
            ->waitFor('@tallstackui_chart')
            ->pause(300)
            ->tap(function (Browser $browser): void {
                $shown = $this->captions($browser);

                Assert::assertLessThan(30, count($shown), 'some labels should be hidden on a narrow plot');
                Assert::assertContains('01/08', $shown, 'the first label always stays');

                foreach ($this->gaps($browser) as $gap) {
                    Assert::assertGreaterThanOrEqual(0, $gap, 'the shown labels must not overlap');
                }
            })
            ->resize(1400, 800)
            ->pause(300)
            ->tap(fn (Browser $browser) => Assert::assertCount(30, $this->captions($browser), 'every label fits again on a wide plot'));
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
    public function keeps_the_tooltip_inside_the_plot_at_the_top(): void
    {
        // The box hangs above its own anchor, so clamping the anchor at zero
        // used to leave the whole tooltip above the plot, out of sight inside
        // any card, which is overflow-hidden.
        Livewire::visit(new ChartComparison)
            ->waitFor('@tallstackui_chart')
            ->tap(function (Browser $browser): void {
                foreach ([0.0, 0.05, 0.5] as $level) {
                    $browser->script($this->hover(0.5, $level));
                    $browser->pause(120);

                    Assert::assertLessThanOrEqual(1, $this->overflow($browser)[2], "tooltip escapes on the top at {$level}");
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

    /** The rendered height of the horizontal axis row. */
    private function axis(Browser $browser): float
    {
        return (float) $browser->script(
            "return document.querySelector('[dusk=tallstackui_chart_caption_0]').parentElement.getBoundingClientRect().height;"
        )[0];
    }

    /** The text of every axis caption that is currently shown. */
    private function captions(Browser $browser): array
    {
        return $browser->script(
            "return [...document.querySelectorAll('[dusk^=tallstackui_chart_caption_]')]
                .filter((label) => getComputedStyle(label).visibility !== 'hidden')
                .map((label) => label.innerText);"
        )[0];
    }

    /** How many series groups are actually painted right now. */
    private function drawn(Browser $browser): int
    {
        return (int) $browser->script(
            "return [...document.querySelectorAll('[dusk=tallstackui_chart] > g')].filter((group) => group.style.display !== 'none').length;"
        )[0];
    }

    /** The horizontal distance between each pair of shown captions, negative when they overlap. */
    private function gaps(Browser $browser, ?int $row = null): array
    {
        $filter = $row === null ? '' : ".filter((label, index) => index % 2 === {$row})";

        return $browser->script(
            "const rects = [...document.querySelectorAll('[dusk^=tallstackui_chart_caption_]')]{$filter}
                .filter((label) => getComputedStyle(label).visibility !== 'hidden')
                .map((label) => label.getBoundingClientRect());
             return rects.slice(1).map((rect, index) => rect.left - rects[index].right);"
        )[0];
    }

    private function hover(float $ratio, float $level = 0.5): string
    {
        // Pointer events rather than mouse ones, which is what also makes the
        // chart reachable from touch.
        return <<<JS
        const plot = document.querySelector('[dusk=tallstackui_chart]');
        const rect = plot.getBoundingClientRect();

        plot.dispatchEvent(new PointerEvent('pointermove', {
            clientX: rect.left + rect.width * {$ratio},
            clientY: rect.top + rect.height * {$level},
            pointerType: 'mouse',
            bubbles: true,
        }));
        JS;
    }

    /** How many pixels the tooltip spills past the left, right and top of the plot. */
    private function overflow(Browser $browser): array
    {
        return $browser->script(
            "const plot = document.querySelector('[dusk=tallstackui_chart]').getBoundingClientRect();
             const tip = document.querySelector('[dusk=tallstackui_chart_tooltip]').getBoundingClientRect();
             return [plot.left - tip.left, tip.right - plot.right, plot.top - tip.top];"
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
