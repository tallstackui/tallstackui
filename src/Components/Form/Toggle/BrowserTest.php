<?php

namespace TallStackUi\Components\Form\Toggle;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
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
    public function can_toggle_when_unlocked(): void
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
}
