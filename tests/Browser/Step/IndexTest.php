<?php

namespace Tests\Browser\Step;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_render_finish_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-step selected="1" helpers>
                        <x-step.items step="1" title="Foo">
                            Foo bar baz
                        </x-step.items>
                        <x-step.items step="2" title="Bar">
                            Baz bar foo
                        </x-step.items>
                        <x-slot:finish>
                            Finish
                        </x-slot:finish>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Foo bar baz')
            ->assertDontSee('Finish')
            ->click('@tallstackui_step_next')
            ->waitForText('Baz bar foo')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->assertSee('Finish');
    }

    /** @test */
    public function can_render_livewire_component(): void
    {
        Livewire::component('test', new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    Foo bar baz through Livewire Component
                </div>
                HTML;
            }
        });

        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-step selected="1">
                        <x-step.items step="1" title="Foo" description="Foo Description">
                            <livewire:test />
                        </x-step.items>
                        <x-step.items step="2" title="Bar" description="Bar Description">
                            Baz bar foo
                        </x-step.items>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Foo bar baz through Livewire Component');
    }

    /** @test */
    public function can_select_with_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $step = 2;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-step wire:model="step">
                        <x-step.items step="1" title="Foo">
                            Foo bar baz
                        </x-step.items>
                        <x-step.items step="2" title="Bar">
                            Baz bar foo
                        </x-step.items>
                    </x-step>

                    <x-button id="change" wire:click="$set('step', 1)" text="Click" />
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
            public int $step = 2;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ $step }}

                    <x-step wire:model.live="step" navigate>
                        <x-step.items step="1" title="Foo">
                            Foo bar baz
                        </x-step.items>
                        <x-step.items step="2" title="Bar">
                            Baz bar foo
                        </x-step.items>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->clickAtXPath('/html/body/div[3]/div/nav/div/ul/li[1]')
            ->waitForText('Foo bar baz')
            ->assertSee('Foo bar baz')
            ->assertSee('Bar')
            ->assertDontSee('Baz bar foo')
            ->clickAtXPath('/html/body/div[3]/div/nav/div/ul/li[2]')
            ->waitForText('Baz bar foo')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }

    /** @test */
    public function can_select_with_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-step selected="1" helpers>
                        <x-step.items step="1" title="Foo">
                            Foo bar baz
                        </x-step.items>
                        <x-step.items step="2" title="Bar">
                            Baz bar foo
                        </x-step.items>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->click('@tallstackui_step_next')
            ->waitForText('Baz bar foo')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }
}
