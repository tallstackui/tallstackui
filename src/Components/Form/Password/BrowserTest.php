<?php

namespace TallStackUi\Components\Form\Password;

use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\OperatingSystem;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
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
            ->waitFor('@generate')
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
            ->waitFor('@reveal')
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

    #[Test]
    public function can_persist_generated_password_on_confirmation_field(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $new_password = null;

            public ?string $new_password_confirmation = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-password label="Nova Senha *"
                                wire:model.live.debounce="new_password"
                                generator
                                :rules="['min', 'symbols', 'numbers', 'mixed']"
                                x-on:generate="$wire.set('new_password_confirmation', $event.detail.password)"
                                required />

                    <x-password dusk="confirmation"
                                label="Confirme a Nova Senha *"
                                wire:model="new_password_confirmation"
                                :rules="['min', 'symbols', 'numbers', 'mixed']"
                                required />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_form_password_generate')
            ->pause(3000)
            ->assertScript('document.querySelector("[dusk=confirmation]").value.length > 0');
    }

    #[Test]
    public function can_persist_generated_password_on_confirmation_field_without_live(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $password = null;

            public ?string $password_confirmation = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-password label="Password"
                                wire:model="password"
                                rules
                                generator
                                x-on:generate="$wire.set('password_confirmation', $event.detail.password)" />

                    <x-password dusk="confirmation"
                                label="Confirm password"
                                wire:model="password_confirmation"
                                rules />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_form_password_generate')
            ->pause(3000)
            ->assertScript('document.querySelector("[dusk=confirmation]").value.length > 0');
    }

    #[Test]
    public function cannot_paste_password_when_using_typing_only(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $password = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <input dusk="copy" value="secret-password" />
                
                    @if ($password)
                        <p dusk="reveal">{{ $password }}</p>
                    @endif
                    
                    <x-password dusk="cant-paste" wire:model.live="password" typing-only />
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->click('@copy')
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'a'])
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'c'])
            ->click('@cant-paste')
            ->keys('@cant-paste', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertNotPresent('@reveal');
    }
}
