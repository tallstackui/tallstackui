<?php

namespace TallStackUi\Components\Loading;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_see_loading_using_spinner_indicator(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-loading loading="save" indicator="spinner.bars" />

                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                sleep(1);
            }
        })
            ->assertSee('Save')
            ->assertMissing('@spinner-bars')
            ->click('@save')
            ->waitFor('@spinner-bars')
            ->assertVisible('@spinner-bars');
    }

    #[Test]
    public function can_see_loading_using_svg(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-loading loading="save" />
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                sleep(1);
            }
        })
            ->assertSee('Save')
            ->assertDontSee('svg')
            ->click('@save')
            ->waitUntil('document.querySelector("svg")');
    }

    #[Test]
    public function can_see_loading_using_text(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-loading loading="save">
                        <p dusk="loading">
                            Loading...
                        </p>
                    </x-loading>
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                sleep(1);
            }
        })
            ->assertSee('Save')
            ->assertDontSee('svg')
            ->click('@save')
            ->waitForTextIn('@loading', 'Loading...');
    }

    #[Test]
    public function can_see_loading_using_text_with_delay_longest(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-loading loading="save" delay="longest">
                        <p dusk="loading">
                            Loading...
                        </p>
                    </x-loading>
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                sleep(4);
            }
        })
            ->assertSee('Save')
            ->assertDontSee('svg')
            ->click('@save')
            ->waitForTextIn('@loading', 'Loading...');
    }

    #[Test]
    public function locks_the_body_overflow_while_the_request_is_in_flight(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-loading loading="save">
                        <p dusk="loading">Loading...</p>
                    </x-loading>

                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                sleep(2);
            }
        })
            ->waitForLivewireToLoad()
            ->tap(fn (Browser $browser) => Assert::assertNotSame('hidden', $this->overflow($browser)))
            ->click('@save')
            ->waitForTextIn('@loading', 'Loading...')
            ->tap(function (Browser $browser): void {
                // The old hook name ('commit.prepare') does not exist in Livewire 4
                // and Livewire.hook does not validate names, so this never ran.
                Assert::assertSame('hidden', $this->overflow($browser), 'the body must be locked while loading');
            })
            ->waitUntilMissingText('Loading...')
            ->pause(500)
            ->tap(fn (Browser $browser) => Assert::assertNotSame('hidden', $this->overflow($browser), 'the lock must be released after the morph'));
    }

    private function overflow(Browser $browser): string
    {
        return $browser->script('return document.body.style.overflow;')[0];
    }
}
