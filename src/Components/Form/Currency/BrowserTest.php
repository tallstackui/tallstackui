<?php

namespace TallStackUi\Components\Form\Currency;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_bind_and_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable mutate />
                    
                    <x-button dusk="sync" wire:click="sync">Reset</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->reset('money');
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '10.00')
            ->assertSeeIn('@money', '10.00')
            ->waitForLivewire()->click('@sync')
            ->assertInputValue('@input', '');
    }

    #[Test]
    public function can_bind_formatted(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable mutate />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '10.00')
            ->assertSeeIn('@money', '10.00');
    }

    #[Test]
    public function can_bind_without_format(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->waitForTextIn('@money', '1000')
            ->assertSeeIn('@money', '1000');
    }

    #[Test]
    public function can_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" locale="pt-BR" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->assertInputValue('@input', '10,00')
            ->click('@tallstackui_form_currency_clearable')
            ->waitForLivewire()
            ->assertInputValue('@input', '')
            ->pause(100)
            ->assertNotVisible('@money');
    }

    #[Test]
    public function can_format_brl(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" locale="pt-BR" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '1000')
            ->assertInputValue('@input', '10,00');
    }

    #[Test]
    public function can_format_correctly(): void
    {
        Livewire::visit(new class extends Component
        {
            public float $money = 1041.3;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->assertInputValue('@input', '1,041.30');
    }

    #[Test]
    public function can_format_with_three_decimals(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>

                    <x-currency dusk="input" wire:model.live="money" :decimals="3" :precision="5" mutate />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->typeSlowly('@input', '12345')
            ->pause(500)
            ->assertInputValue('@input', '12.345');
    }

    #[Test]
    public function can_see_validation_error(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $money = '10.00';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->click('@tallstackui_form_currency_clearable')
            ->pause(250)
            ->assertSee('The money field is required.');
    }

    #[Test]
    public function cannot_insert_nan(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(250)
            ->typeSlowly('@input', 'n')
            ->typeSlowly('@input', 'a')
            ->typeSlowly('@input', 'n')
            ->typeSlowly('@input', 'nan')
            ->assertNotVisible('@money');
    }
}
