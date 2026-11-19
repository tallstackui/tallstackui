<?php

namespace TallStackUi\Components\Switcher;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_emit_switch_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $logged = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($logged)
                        <p dusk="logged">{{ $logged }}</p>
                    @endif

                    <x-switcher on="Pause" off="Play" x-on:switch="$wire.set('logged', 'value:' + $event.detail.value)" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@logged', 'value:true')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@logged', 'value:false');
    }

    #[Test]
    public function can_follow_the_server(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-switcher wire:model="playing" on="Pause" off="Play" />

                    <button type="button" dusk="server" wire:click="$toggle('playing')">Toggle</button>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertVisible('@tallstackui_switcher_off')
            ->click('@server')
            ->waitFor('@tallstackui_switcher_on')
            ->waitUntilMissing('@tallstackui_switcher_off')
            ->click('@server')
            ->waitFor('@tallstackui_switcher_off')
            ->waitUntilMissing('@tallstackui_switcher_on');
    }

    #[Test]
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-switcher on="Pause" off="Play" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertVisible('@tallstackui_switcher_off')
            ->assertMissing('@tallstackui_switcher_on')
            ->assertSee('Play')
            ->assertDontSee('Pause');
    }

    #[Test]
    public function can_render_icons(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-switcher icon-on="sun" icon-off="moon" rotate />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertVisible('@tallstackui_switcher_off svg')
            ->assertMissing('@tallstackui_switcher_on')
            ->click('@tallstackui_switcher')
            ->waitFor('@tallstackui_switcher_on svg')
            ->waitUntilMissing('@tallstackui_switcher_off');
    }

    #[Test]
    public function can_start_from_wire_model_value(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = true;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-switcher wire:model="playing" on="Pause" off="Play" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitFor('@tallstackui_switcher_on')
            ->assertSee('Pause')
            ->assertDontSee('Play');
    }

    #[Test]
    public function can_switch(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $playing ? 'playing' : 'stopped' }}</p>

                    <x-switcher wire:model.live="playing" on="Pause" off="Play" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@state', 'stopped')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@state', 'playing')
            ->waitFor('@tallstackui_switcher_on')
            ->waitUntilMissing('@tallstackui_switcher_off')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@state', 'stopped')
            ->waitFor('@tallstackui_switcher_off')
            ->waitUntilMissing('@tallstackui_switcher_on');
    }

    #[Test]
    public function can_switch_flipping(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $playing ? 'playing' : 'stopped' }}</p>

                    <x-switcher wire:model.live="playing" on="Pause" off="Play" flip />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertVisible('@tallstackui_switcher_off')
            ->assertMissing('@tallstackui_switcher_on')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@state', 'playing')
            ->waitFor('@tallstackui_switcher_on')
            ->waitUntilMissing('@tallstackui_switcher_off');
    }

    #[Test]
    public function can_switch_with_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $playing ? 'playing' : 'stopped' }}</p>

                    <x-switcher wire:model.live="playing" on="Pause" off="Play" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@state', 'stopped')
            ->keys('@tallstackui_switcher input', '{space}')
            ->waitForTextIn('@state', 'playing');
    }

    #[Test]
    public function cannot_switch_when_disabled(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $playing ? 'playing' : 'stopped' }}</p>

                    <x-switcher wire:model.live="playing" on="Pause" off="Play" disabled />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@state', 'stopped')
            ->click('@tallstackui_switcher')
            ->pause(500)
            ->assertSeeIn('@state', 'stopped')
            ->assertVisible('@tallstackui_switcher_off')
            ->assertMissing('@tallstackui_switcher_on');
    }

    #[Test]
    public function cannot_switch_when_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public bool $playing = true;

            public ?string $logged = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="state">{{ $playing ? 'playing' : 'stopped' }}</p>

                    <p dusk="logged">{{ $logged ?? 'silent' }}</p>

                    <x-switcher wire:model.live="playing" on="Pause" off="Play" readonly x-on:switch="$wire.set('logged', 'fired')" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@state', 'playing')
            ->click('@tallstackui_switcher')
            ->pause(500)
            ->assertSeeIn('@state', 'playing')
            ->assertSeeIn('@logged', 'silent')
            ->assertVisible('@tallstackui_switcher_on')
            ->assertMissing('@tallstackui_switcher_off');
    }
}
