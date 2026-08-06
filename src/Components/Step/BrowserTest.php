<?php

namespace TallStackUi\Components\Step;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_navigate_with_a_custom_next_slot(): void
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
                        <x-slot:next>
                            <button type="button" dusk="custom_next" x-on:click="next()">Advance</button>
                        </x-slot:next>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->click('@custom_next')
            ->waitForText('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }

    #[Test]
    public function can_navigate_with_the_compact_helpers(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-step selected="1" helpers="compact" navigate-previous>
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
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->click('@tallstackui_step_next')
            ->waitForText('Baz bar foo')
            ->assertDontSee('Foo bar baz')
            ->click('@tallstackui_step_previous')
            ->waitForText('Foo bar baz')
            ->assertDontSee('Baz bar foo');
    }

    #[Test]
    public function can_navigate_with_the_minimal_helpers(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-step selected="1" helpers="minimal">
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
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->click('@tallstackui_step_next')
            ->waitForText('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }

    #[Test]
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

    #[Test]
    public function can_render_id_on_step_navigation(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-step selected="1">
                        <x-step.items step="1" title="Foo" id="wizard-step-one">
                            Foo bar baz
                        </x-step.items>
                        <x-step.items step="2" title="Bar" id="wizard-step-two">
                            Baz bar foo
                        </x-step.items>
                    </x-step>
                </div>
                HTML;
            }
        })
            ->waitFor('#li-wizard-step-one')
            ->assertPresent('#li-wizard-step-one')
            ->assertPresent('#li-wizard-step-two')
            ->waitFor('#div-wizard-step-one')
            ->assertPresent('#div-wizard-step-one')
            ->assertPresent('#div-wizard-step-two');
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function can_select_with_entangle_live(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $step = 1;

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
            ->assertSee('Foo bar baz')
            ->assertDontSee('Baz bar foo')
            ->clickAtVisibleXPath('/html/body/div[3]/div/nav/ul/li[2]')
            ->waitForText('Baz bar foo')
            ->assertSee('Baz bar foo')
            ->assertDontSee('Foo bar baz');
    }

    #[Test]
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
