<?php

namespace TallStackUi\Components\Form\Range;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_increase(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>

                    <x-range wire:model="quantity" />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->setRangeValue(75)))
            ->click('@sync')
            ->waitForTextIn('@increased', '75', 10)
            ->assertSeeIn('@increased', '75');
    }

    #[Test]
    public function can_increase_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>

                    <x-range wire:model.live="quantity" />
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->setRangeValue(75)))
            ->waitForTextIn('@increased', '75', 10)
            ->assertSeeIn('@increased', '75');
    }

    private function setRangeValue(int $value): string
    {
        return <<<JS
            const input = document.querySelector('[dusk="tallstackui_form_range_input"]');
            const nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
            nativeSetter.call(input, {$value});
            input.dispatchEvent(new Event('input', { bubbles: true }));
        JS;
    }
}
