<?php

namespace Tests\Browser\Loading;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_see_loading_using_svg(): void
    {
        /** @var Browser $browser */
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

    /** @test */
    public function can_see_loading_using_text(): void
    {
        /** @var Browser $browser */
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

    /** @test */
    public function can_see_loading_using_text_with_delay_longest(): void
    {
        /** @var Browser $browser */
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
}
