<?php

namespace Tests\Browser\Slide;

use Laravel\Dusk\Browser;
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
            ->clickAtPoint(350, 350)
            ->waitUntilMissingText('Foo bar')
            ->assertDontSee('Foo bar')
            ->assertSeeIn('@target', 'Closed');
    }

    /** @test */
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

    /** @test */
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
            ->waitForText(['Foo bar', 'Lorem'])
            ->assertSee('Foo bar')
            ->assertSee('Lorem');
    }

    /** @test */
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

    /** @test */
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
                
                    <x-button dusk="open" x-on:click="$slideOpen('test')">Open</x-button>
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
    public function cannot_close_when_slide_is_persistent(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-slide id="persistent" persistent>
                        Foo bar
                    </x-slide>
                
                    <x-button dusk="open" x-on:click="$slideOpen('persistent')">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->assertSee('Open')
            ->assertDontSee('Foo bar')
            ->click('@open')
            ->waitForText('Foo bar')
            ->clickAtPoint(350, 350)
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div')
            ->pause(150)
            ->assertSee('Foo bar');
    }
}
