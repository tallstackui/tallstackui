<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class PasswordTest extends BrowserTestCase
{
    #[Test]
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

    #[Test]
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

    #[Test]
    public function can_generate_with_default_rules(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $password = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($password)
                        <p dusk="reveal">{{ $password }}</p>
                    @endif
                    
                    <x-password generator x-on:generate="$wire.set('password', $event.detail.password)" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()
            ->waitForLivewire()
            ->click('@tallstackui_form_password_generate')
            ->assertPresent('@reveal');
    }

    #[Test]
    public function can_use_a_custom_generator_rule(): void
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
                                generator
                                x-on:generate="$wire.set('reveal', 1)" />
                </div>

                <script>
                    window.TallStackUi = window.TallStackUi || {};
                
                    window.TallStackUi.passwordGenerator = function (min, mixed, numbers, symbols) {
                        console.log(min, mixed, numbers, symbols);
                
                        return 'abcedf';
                    };
                </script> 
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()
            ->waitForLivewire()
            ->click('@tallstackui_form_password_generate')
            ->waitForTextIn('@reveal', 'abcedf')
            ->assertSeeIn('@reveal', 'abcedf');
    }
}
