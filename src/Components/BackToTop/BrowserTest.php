<?php

namespace TallStackUi\Components\BackToTop;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div style="height: 2000px;">Content</div>
                    <x-back-to-top />
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_back_to_top');
    }

    #[Test]
    public function is_hidden_at_top_of_page(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div style="height: 2000px;">Content</div>
                    <x-back-to-top />
                </div>
                HTML;
            }
        })
            ->assertMissing('@tallstackui_back_to_top');
    }

    #[Test]
    public function scrolls_to_top_on_click(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div style="height: 2000px;">Content</div>
                    <x-back-to-top immediate />
                </div>
                HTML;
            }
        });

        $browser->script('window.scrollTo(0, 500)');

        $browser->waitFor('@tallstackui_back_to_top')
            ->assertVisible('@tallstackui_back_to_top')
            ->click('@tallstackui_back_to_top')
            ->waitUntilMissing('@tallstackui_back_to_top');
    }

    #[Test]
    public function shows_after_scrolling(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div style="height: 2000px;">Content</div>
                    <x-back-to-top />
                </div>
                HTML;
            }
        });

        $browser->assertMissing('@tallstackui_back_to_top');
        $browser->script('window.scrollTo(0, 500)');

        $browser->waitFor('@tallstackui_back_to_top')
            ->assertVisible('@tallstackui_back_to_top');
    }

    #[Test]
    public function shows_with_anchor_observer(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <div id="hero" style="height: 100px;">Hero</div>
                    <div style="height: 2000px;">Content</div>
                    <x-back-to-top anchor="#hero" />
                </div>
                HTML;
            }
        });

        $browser->assertMissing('@tallstackui_back_to_top');
        $browser->script('window.scrollTo(0, 500)');

        $browser->waitFor('@tallstackui_back_to_top')
            ->assertVisible('@tallstackui_back_to_top');
    }
}
