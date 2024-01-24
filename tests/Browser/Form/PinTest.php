<?php

namespace Tests\Browser\Form;

use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\OperatingSystem;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class PinTest extends BrowserTestCase
{
    /** @test */
    public function can_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="4" label="Foo" hint="Test" wire:model.live="value" clear />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForLivewireToLoad()->click('@form_pin_clear')
            ->waitUntilMissingText('1515')
            ->assertDontSeeIn('@value', '1515');
    }

    /** @test */
    public function can_clear_the_inputs_when_clear_property_externally(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($value)
                        <p dusk="value">{{ $value }}</p>
                    @endif
                    
                    <x-pin length="4" label="Foo" hint="Test" wire:model.live="value" clear />
                    
                    <x-button dusk="clear" wire:click="$set('value')">Clear</x-button>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForLivewire()->click('@clear')
            ->assertNotPresent('@value');
    }

    /** @test */
    public function can_fill(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="1" label="Foo" hint="Test" wire:model.live="value" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->waitForLivewire()->type('@pin-1', '1')
            ->waitForTextIn('@value', '1')
            ->assertSeeIn('@value', '1');
    }

    /** @test */
    public function can_fill_and_dispatch_change_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="1" label="Foo" hint="Test" wire:model="value" wire:change="sync" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->type('@pin-1', '1')
            ->waitForTextIn('@value', '1')
            ->assertSeeIn('@value', '1');
    }

    /** @test */
    public function can_fill_multiples(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="2" label="Foo" hint="Test" wire:model.live="value" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->waitForLivewire()->type('@pin-1', '1')
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[2]')
            ->waitForLivewire()->type('@pin-2', '5')
            ->waitForTextIn('@value', '15')
            ->assertSeeIn('@value', '15');
    }

    /** @test */
    public function can_paste_letters(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <input dusk="copy" value="FOO" />
                    
                    <x-pin length="3" label="Foo" hint="Test" wire:model.live="value" letters />
                </div>
                HTML;
            }
        })
            ->click('@copy')
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'a'])
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'c'])
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->keys('@pin-1', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->waitForTextIn('@value', 'FOO')
            ->assertSeeIn('@value', 'FOO');
    }

    /** @test */
    public function can_paste_numbers(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <input dusk="copy" value="15" />
                    
                    <x-pin length="2" label="Foo" hint="Test" wire:model.live="value" numbers />
                </div>
                HTML;
            }
        })
            ->click('@copy')
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'a'])
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'c'])
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->keys('@pin-1', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->waitForTextIn('@value', '15')
            ->assertSeeIn('@value', '15');
    }

    /** @test */
    public function can_see_prefix(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-pin prefix="G-" length="4" label="Foo" hint="Test" wire:model="value" />
                </div>
                HTML;
            }
        })->assertVisible('@form_pin_prefix');
    }

    /** @test */
    public function can_see_validation_error(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="4" label="Foo" hint="Test" wire:model="value" clear />
                    
                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->click('@form_pin_clear')
            ->waitForLivewire()->click('@sync')
            ->waitUntilMissingText('1515')
            ->assertDontSeeIn('@value', '1515')
            ->assertSee('The value field is required.');
    }

    /** @test */
    public function cannot_paste_letters_when_numbers(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <input dusk="copy" value="FOO" />
                    
                    <x-pin length="3" label="Foo" hint="Test" wire:model.live="value" numbers />
                </div>
                HTML;
            }
        })
            ->click('@copy')
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'a'])
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'c'])
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->keys('@pin-1', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertDontSee('FOO');
    }

    /** @test */
    public function cannot_paste_numbers_when_letters(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <input dusk="copy" value="15" />
                    
                    <x-pin length="2" label="Foo" hint="Test" wire:model.live="value" letters />
                </div>
                HTML;
            }
        })
            ->click('@copy')
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'a'])
            ->keys('@copy', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'c'])
            ->clickAtXPath('/html/body/div[3]/div[2]/div/div/input[1]')
            ->keys('@pin-1', [OperatingSystem::onMac() ? WebDriverKeys::COMMAND : WebDriverKeys::CONTROL, 'v'])
            ->assertDontSee('15');
    }

    /** @test */
    public function cannot_see_clear_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="4" label="Foo" hint="Test" wire:model.live="value" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })->assertNotPresent('@form_pin_clear');
    }

    /** @test */
    public function cannot_see_validation_error_when_invalidate(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $value = '1515';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin length="4" label="Foo" hint="Test" wire:model="value" clear invalidate />
                    
                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->click('@form_pin_clear')
            ->waitForLivewire()->click('@sync')
            ->waitUntilMissingText('1515')
            ->assertDontSeeIn('@value', '1515')
            ->assertDontSee('The value field is required.');
    }
}
