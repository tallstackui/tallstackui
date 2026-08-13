<?php

namespace TallStackUi\Components\Form\Toggle;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_toggle(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $active ? 'on' : 'off' }}</p>

                    <x-toggle wire:model.live="active" label="Foo" dusk="toggle" />
                </div>
                HTML;
            }
        })
            ->assertSeeIn('@state', 'off')
            ->click('@toggle')
            ->waitForTextIn('@state', 'on')
            ->assertSeeIn('@state', 'on');
    }

    #[Test]
    public function can_toggle_through_the_label(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $active ? 'on' : 'off' }}</p>

                    <x-toggle wire:model.live="active" label="Foo" />
                </div>
                HTML;
            }
        })
            ->assertSeeIn('@state', 'off')
            ->clickAtVisibleXPath('//label//span[text()[contains(., "Foo")]]')
            ->waitForTextIn('@state', 'on')
            ->assertSeeIn('@state', 'on');
    }

    #[Test]
    public function cannot_toggle_through_the_label_when_locked(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $active ? 'on' : 'off' }}</p>

                    <x-toggle wire:model.live="active" label="Foo" readonly />
                </div>
                HTML;
            }
        })
            ->assertSeeIn('@state', 'off')
            ->clickAtVisibleXPath('//label//span[text()[contains(., "Foo")]]')
            ->pause(500)
            ->assertSeeIn('@state', 'off');
    }

    #[Test]
    public function cannot_toggle_when_disabled(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $active ? 'on' : 'off' }}</p>

                    <x-toggle wire:model.live="active" label="Foo" dusk="toggle" disabled />
                </div>
                HTML;
            }
        })
            ->assertSeeIn('@state', 'off')
            ->click('@toggle')
            ->pause(500)
            ->assertSeeIn('@state', 'off');
    }

    #[Test]
    public function cannot_toggle_when_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $active ? 'on' : 'off' }}</p>

                    <x-toggle wire:model.live="active" label="Foo" dusk="toggle" readonly />
                </div>
                HTML;
            }
        })
            ->assertSeeIn('@state', 'off')
            ->click('@toggle')
            ->pause(500)
            ->assertSeeIn('@state', 'off');
    }

    #[Test]
    public function keeps_submitting_when_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $active = true;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-toggle wire:model.live="active" label="Foo" dusk="toggle" readonly />
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->assertScript(
                'document.querySelector(\'input[type="checkbox"]\').disabled',
                false
            ))
            ->assertChecked('input[type="checkbox"]');
    }
}
