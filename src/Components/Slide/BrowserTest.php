<?php

namespace TallStackUi\Components\Slide;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $slide = false;

            public string $target = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="target">{{ $target }}</p>

                    <x-slide wire x-on:open="$wire.set('target', 'Opened')" x-on:close="$wire.set('target', 'Closed')">
                        Foo bar
                    </x-slide>

                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
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
            ->pause(800)
            ->click('@tallstackui_slide_close')
            ->waitUntilMissingText('Foo bar')
            ->assertDontSee('Foo bar')
            ->assertSeeIn('@target', 'Closed');
    }

    #[Test]
    public function can_open(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $slide = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire>
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
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
            public bool $slide = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire title="Bar baz">
                        Foo bar
                        <x-slot:footer start>
                            Lorem                
                        </x-slot:footer>
                    </x-slide>
                
                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForAllText(['Foo bar', 'Lorem'])
            ->assertSee('Foo bar')
            ->assertSee('Lorem');
    }

    #[Test]
    public function can_open_and_see_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $slide = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire title="Bar baz">
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->assertDontSee('Bar baz')
            ->click('@open')
            ->waitForAllText(['Foo bar', 'Bar baz'])
            ->assertSee('Foo bar')
            ->assertSee('Bar baz');
    }

    #[Test]
    public function can_open_in_bottom(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $slide = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire bottom>
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
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
    public function can_open_in_top(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $slide = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire top>
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" wire:click="$toggle('slide')">Open</x-button>
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
    public function can_open_using_different_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $test = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide wire="test">
                        Foo bar
                    </x-slide>
                
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
                    <x-slide id="test">
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" x-on:click="$tsui.open.slide('test')">Open</x-button>
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
    public function cannot_close_when_slide_is_persistent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide id="persistent" persistent>
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" x-on:click="$tsui.open.slide('persistent')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->clickAtPoint(350, 350)
            ->clickAtPoint(20, 200)
            ->pause(150)
            ->assertSee('Foo bar');
    }
}
