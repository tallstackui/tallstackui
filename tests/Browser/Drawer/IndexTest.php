<?php

namespace Tests\Browser\Drawer;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $drawer = false;

            public string $target = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="target">{{ $target }}</p>

                    <x-drawer wire title="Drawer" x-on:open="$wire.set('target', 'Opened')" x-on:close="$wire.set('target', 'Closed')">
                        Foo bar
                    </x-drawer>
                
                    <x-button dusk="open" wire:click="$toggle('drawer')">Open</x-button>
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

    #[Test]
    public function can_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $drawer = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer wire>
                        Foo bar
                    </x-drawer>
                
                    <x-button dusk="open" wire:click="$toggle('drawer')">Open</x-button>
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

    #[Test]
    public function can_open_and_see_footer(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $drawer = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer wire title="Bar baz">
                        Foo bar
                        <x-slot:footer>
                            Lorem                
                        </x-slot:footer>
                    </x-drawer>
                
                    <x-button dusk="open" wire:click="$toggle('drawer')">Open</x-button>
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

    #[Test]
    public function can_open_and_see_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $drawer = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer wire title="Bar baz">
                        Foo bar
                    </x-drawer>
                
                    <x-button dusk="open" wire:click="$toggle('drawer')">Open</x-button>
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

    #[Test]
    public function can_open_using_different_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $test = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer wire="test">
                        Foo bar
                    </x-drawer>
                
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

    #[Test]
    public function can_open_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer id="test">
                        Foo bar
                    </x-drawer>
                
                    <x-button dusk="open" x-on:click="$drawerOpen('test')">Open</x-button>
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

    #[Test]
    public function cannot_close_when_drawer_is_persistent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-drawer id="persistent" persistent>
                        Foo bar
                    </x-drawer>
                
                    <x-button dusk="open" x-on:click="$drawerOpen('persistent')">Open</x-button>
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
