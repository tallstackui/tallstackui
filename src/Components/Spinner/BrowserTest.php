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
