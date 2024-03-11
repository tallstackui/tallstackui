<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class PasswordTest extends BrowserTestCase
{
    /** @test */
    public function can_dispatch_event_when_generate(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $password = null;

            public ?string $generate = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($generate)
                        <p dusk="generate">{{ $password }}</p>
                    @endif
                    
                    <x-password dusk="input" 
                                wire:model.live="password"
                                :rules="['min', 'symbols', 'numbers', 'mixed']"
                                generator 
                                x-on:generate="$wire.set('generate', 1)" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->typeSlowly('@input', '123')
            ->waitForLivewire()->click('@tallstackui_form_password_generate')
            ->assertVisible('@generate');
    }

    /** @test */
    public function can_dispatch_event_when_reveal(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $password = null;

            public ?string $reveal = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($reveal)
                        <p dusk="reveal">{{ $password }}</p>
                    @endif
                    
                    <x-password dusk="input" 
                                wire:model.live="password" 
                                x-on:reveal="$wire.set('reveal', '123')" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->typeSlowly('@input', '123')
            ->waitForLivewire()->click('@tallstackui_form_password_reveal')
            ->assertVisible('@reveal')
            ->assertSeeIn('@reveal', '123');
    }
}
