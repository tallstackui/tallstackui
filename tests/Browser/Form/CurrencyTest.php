<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class CurrencyTest extends BrowserTestCase
{    
    /** @test */
    public function formatting_money_correctly(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="currency_input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@currency_input', '1000')
            ->pause(500)
            ->assertSeeIn('@money', '10.00');
    }

    /** @test */
    public function formatting_money_correctly_with_locale(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="currency_input" wire:model.live="money" clearable locale="pt-BR" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@currency_input', '1000')
            ->pause(500)
            ->assertSeeIn('@money', '10,00');
    }

    /** @test */
    public function does_not_allow_letters(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $money = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="money">{{ $money }}</p>
                
                    <x-currency dusk="currency_input" wire:model.live="money" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@currency_input', '1000')
            ->pause(500)
            ->assertSeeIn('@money', '10.00')
            ->type('@currency_input', '1000a')
            ->pause(500)
            ->assertSeeIn('@money', '10.00');
    }
}
