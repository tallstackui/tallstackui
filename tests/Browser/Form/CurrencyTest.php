<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class CurrencyTest extends BrowserTestCase
{
    #[Test]
    public function can_bind_formatted(): void
    {
        $this->markTestSkipped();

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
}
