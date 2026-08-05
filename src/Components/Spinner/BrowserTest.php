<?php

namespace TallStackUi\Components\Spinner;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use TallStackUi\Components\Spinner\Component as Spinner;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function shimmer_paints_a_visible_gradient(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-spinner shimmer text="Loading" />
                </div>
                HTML;
            }
        });

        $browser->waitFor('@spinner-shimmer');

        // The gradient stops are currentColor, so painting the fill transparent
        // through color would collapse every stop to transparent as well.
        $probe = json_decode($browser->script(<<<'JS'
            const element = document.querySelector('[dusk="spinner-shimmer"]');
            const style = getComputedStyle(element);

            return JSON.stringify({
                color: style.color,
                fill: style.webkitTextFillColor,
                background: style.backgroundImage,
            });
        JS)[0], true);

        $this->assertSame('rgba(0, 0, 0, 0)', $probe['fill']);
        $this->assertNotSame('rgba(0, 0, 0, 0)', $probe['color']);
        $this->assertStringNotContainsString('oklab(0 0 0 / 0)', $probe['background']);
        $this->assertStringNotContainsString('rgba(0, 0, 0, 0)', $probe['background']);
    }

    #[Test]
    public function staggered_variants_start_at_the_first_keyframe(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-spinner wave />
                    <x-spinner bars />
                    <x-spinner typing />
                    <x-spinner dots />
                </div>
                HTML;
            }
        });

        $browser->waitFor('@spinner-wave');

        // Cloning restarts the animations, so reading the clone synchronously
        // captures the state before any of them has advanced. Without
        // `backwards` the delayed children render at their static state
        // instead of the 0% keyframe, flashing at full scale.
        $probe = json_decode($browser->script(<<<'JS'
            const read = (name) => {
                const clone = document.querySelector(`[dusk="spinner-${name}"]`).cloneNode(true);

                document.body.appendChild(clone);

                const children = [...clone.children].map((child) => {
                    const style = getComputedStyle(child);

                    return { fill: style.animationFillMode, transform: style.transform };
                });

                clone.remove();

                return [name, children];
            };

            return JSON.stringify(Object.fromEntries(['wave', 'bars', 'typing', 'dots'].map(read)));
        JS)[0], true);

        foreach ($probe as $variant => $children) {
            foreach ($children as $index => $child) {
                $this->assertSame('backwards', $child['fill'], "{$variant}[{$index}] must fill backwards");
                $this->assertNotSame('none', $child['transform'], "{$variant}[{$index}] must start at the 0% keyframe");
            }

            $this->assertCount(1, array_unique(array_column($children, 'transform')),
                "every {$variant} child must start from the same keyframe");
        }
    }

    #[Test]
    public function thinking_cycles_the_braille_frames(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-spinner thinking :interval="80" />
                </div>
                HTML;
            }
        });

        $browser->waitFor('@spinner-thinking-glyph');

        $first = $browser->text('@spinner-thinking-glyph');

        $browser->waitUsing(5, 50, fn () => $browser->text('@spinner-thinking-glyph') !== $first);

        $this->assertNotSame($first, $browser->text('@spinner-thinking-glyph'));
    }

    #[Test]
    public function thinking_is_removed_along_with_the_element(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public bool $show = true;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($show)
                        <x-spinner thinking :interval="80" />
                    @endif
                    <button dusk="toggle"
                            type="button"
                            wire:click="$set('show', false)">Hide</button>
                </div>
                HTML;
            }
        });

        $browser->waitFor('@spinner-thinking-glyph')
            ->click('@toggle')
            ->waitUntilMissing('@spinner-thinking-glyph')
            ->assertMissing('@spinner-thinking-glyph');
    }

    #[Test]
    public function thinking_only_renders_glyphs_from_the_frame_set(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-spinner thinking :interval="80" />
                </div>
                HTML;
            }
        });

        $browser->waitFor('@spinner-thinking-glyph');

        foreach (range(1, 5) as $attempt) {
            $this->assertContains($browser->text('@spinner-thinking-glyph'), Spinner::FRAMES);

            $browser->pause(90);
        }
    }
}
