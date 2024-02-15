<?php

namespace Tests\Browser\Tab;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_select(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-tab selected="Foo">
                        <x-tab.items tab="Foo">
                            Foo bar baz
                        </x-tab.items>
                        <x-tab.items tab="Bar">
                            Baz bar foo
                        </x-tab.items>
                    </x-tab>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            
            ->clickAtXPath('/html/body/div[3]/div/ul/li[2]')
            ->waitForText('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }

    /** @test */
    public function can_select_and_deselect(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-tab selected="Foo">
                        <x-tab.items tab="Foo">
                            Foo bar baz
                        </x-tab.items>
                        <x-tab.items tab="Bar">
                            Baz bar foo
                        </x-tab.items>
                    </x-tab>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->clickAtXPath('/html/body/div[3]/div/ul/li[2]')
            ->waitForText('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->clickAtXPath('/html/body/div[3]/div/ul/li[1]')
            ->waitForText('Foo bar baz')
            ->assertDontSee('Baz bar foo');
    }

    /** @test */
    public function can_select_with_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $tab = 'Bar';

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-tab wire:model="tab">
                        <x-tab.items tab="Foo">
                            Foo bar baz
                        </x-tab.items>
                        <x-tab.items tab="Bar">
                            Baz bar foo
                        </x-tab.items>
                    </x-tab>

                    <x-button id="change" wire:click="$set('tab', 'Foo')" text="Click" />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->click('#change')
            ->waitForText('Foo bar baz')
            ->assertDontSee('Baz bar foo');
    }

    /** @test */
    public function can_select_with_entangle_live(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $tab = 'Bar';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ $tab }}

                    <x-tab wire:model.live="tab">
                        <x-tab.items tab="Foo">
                            Foo bar baz
                        </x-tab.items>
                        <x-tab.items tab="Bar">
                            Baz bar foo
                        </x-tab.items>
                    </x-tab>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->clickAtXPath('/html/body/div[3]/div/ul/li[1]')
            ->waitForText('Foo bar baz')
            ->assertSee('Bar')
            ->assertDontSee('Baz bar foo');
    }
}
