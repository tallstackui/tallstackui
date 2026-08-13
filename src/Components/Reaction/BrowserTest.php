<?php

namespace TallStackUi\Components\Reaction;

use Facebook\WebDriver\WebDriverBy;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_open_and_close_the_panel_via_hover(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="outside">Outside</p>

                    <x-reaction hover />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                //
            }
        })
            ->assertPresent('@tallstackui_reaction_button')
            ->assertMissing('@tallstackui_reaction_popover');

        $browser->driver->action()
            ->moveToElement($browser->driver->findElement(WebDriverBy::cssSelector('[dusk="tallstackui_reaction_button"]')))
            ->perform();

        $browser->waitFor('@tallstackui_reaction_popover')
            ->assertAttribute('@tallstackui_reaction_popover', 'data-show', '');

        $browser->driver->action()
            ->moveToElement($browser->driver->findElement(WebDriverBy::cssSelector('[dusk="outside"]')))
            ->perform();

        $browser->pause(500)
            ->assertAttributeMissing('@tallstackui_reaction_popover', 'data-show');
    }

    #[Test]
    public function can_open_the_panel_instantly_when_delay_is_flash(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-reaction delay="flash" />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                //
            }
        })
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_popover')
            ->assertAttribute('@tallstackui_reaction_popover', 'data-instant', '');
    }

    #[Test]
    public function can_open_the_panel_with_a_balloon_color(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-reaction balloon="red" />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                //
            }
        })
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_popover')
            ->assertAttribute('@tallstackui_reaction_popover', 'data-color', 'red');
    }

    #[Test]
    public function can_open_the_panel_with_a_named_delay(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-reaction delay="slow" />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                //
            }
        })
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_popover')
            ->assertAttribute('@tallstackui_reaction_popover', 'data-delay', 'slow');
    }

    #[Test]
    public function can_react(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $reaction = '';

            public int $quantity = 1;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="reaction">{{ $reaction }}</p>

                    <x-reaction wire:model.live="quantity" :$quantity />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                $this->reaction = $reaction;

                $this->quantity++;
            }
        })
            ->assertDontSeeIn('@reaction', 'thumbs-up')
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_thumbs-up')
            ->click('@tallstackui_reaction_thumbs-up')
            ->waitForTextIn('@reaction', 'thumbs-up')
            ->assertSeeIn('@reaction', 'thumbs-up')
            ->assertSee('2');
    }

    #[Test]
    public function can_react_using_custom_method(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $reaction = '';

            public int $quantity = 1;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="reaction">{{ $reaction }}</p>

                    <x-reaction wire:model.live="quantity" :$quantity react-method="fooBar" />
                </div>
                HTML;
            }

            public function fooBar(string $reaction): void
            {
                $this->reaction = $reaction;

                $this->quantity++;
            }
        })
            ->assertDontSeeIn('@reaction', 'thumbs-up')
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_thumbs-up')
            ->click('@tallstackui_reaction_thumbs-up')
            ->waitForTextIn('@reaction', 'thumbs-up')
            ->assertSeeIn('@reaction', 'thumbs-up')
            ->assertSee('2');
    }

    #[Test]
    public function can_render_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $reaction = '';

            public int $quantity = 1;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="reaction">{{ $reaction }}</p>

                    <x-reaction>
                        FooBar
                    </x-reaction>
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                $this->reaction = $reaction;

                $this->quantity++;
            }
        })->assertSee('FooBar');
    }

    #[Test]
    public function can_use_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $reaction = '';

            public int $quantity = 1;

            public ?string $reacted = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($reacted)
                        <p dusk="reacted">{{ $reacted }}</p>
                    @endif
                
                    <p dusk="reaction">{{ $reaction }}</p>

                    <x-reaction wire:model.live="quantity" 
                                :$quantity 
                                x-on:react="$wire.set('reacted', 'Reacted')" />
                </div>
                HTML;
            }

            public function react(string $reaction): void
            {
                $this->reaction = $reaction;

                $this->quantity++;
            }
        })
            ->assertDontSeeIn('@reaction', 'thumbs-up')
            ->click('@tallstackui_reaction_button')
            ->waitFor('@tallstackui_reaction_thumbs-up')
            ->click('@tallstackui_reaction_thumbs-up')
            ->waitForTextIn('@reaction', 'thumbs-up')
            ->assertSeeIn('@reaction', 'thumbs-up')
            ->assertSee('2')
            ->assertVisible('@reacted')
            ->assertSeeIn('@reacted', 'Reacted');
    }
}
