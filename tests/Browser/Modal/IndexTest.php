<?php

namespace Tests\Browser\Modal;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public string $target = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="target">{{ $target }}</p>

                    <x-modal wire title="Modal" x-on:open="$wire.set('target', 'Opened')" x-on:close="$wire.set('target', 'Closed')">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar')
            ->assertSeeIn('@target', 'Opened')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/div/div[1]/button')
            ->waitUntilMissingText('Foo bar')
            ->assertDontSee('Foo bar')
            ->assertSeeIn('@target', 'Closed');
    }

    /** @test */
    public function can_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire>
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    /** @test */
    public function can_open_and_see_footer(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire title="Bar baz">
                        Foo bar
                        <x-slot:footer>
                            Lorem                
                        </x-slot:footer>
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForText(['Foo bar', 'Lorem'])
            ->assertSee('Foo bar')
            ->assertSee('Lorem');
    }

    /** @test */
    public function can_open_and_see_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $modal = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire title="Bar baz">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('modal')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForText(['Foo bar', 'Bar baz'])
            ->assertSee('Foo bar')
            ->assertSee('Bar baz');
    }

    /** @test */
    public function can_open_using_different_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $test = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal wire="test">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" wire:click="$toggle('test')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    /** @test */
    public function can_open_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal id="test">
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" x-on:click="$modalOpen('test')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }

    /** @test */
    public function cannot_close_when_modal_is_persistent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-modal id="persistent" persistent>
                        Foo bar
                    </x-modal>
                
                    <x-button dusk="open" x-on:click="$modalOpen('persistent')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->clickAtPoint(350, 350)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div')
            ->waitForText('Foo bar')
            ->assertSee('Foo bar');
    }
}
