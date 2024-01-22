<?php

namespace Tests\Browser\Reaction;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
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
            ->clickAtXPath('html/body/div[3]/div/div/div/div[1]/div/button[7]')
            ->waitForTextIn('@reaction', 'thumbs-up')
            ->assertSeeIn('@reaction', 'thumbs-up')
            ->assertSee('2');
    }

    /** @test */
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
            ->clickAtXPath('html/body/div[3]/div/div/div/div[1]/div/button[7]')
            ->waitForTextIn('@reaction', 'thumbs-up')
            ->assertSeeIn('@reaction', 'thumbs-up')
            ->assertSee('2');
    }

    /** @test */
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
}
