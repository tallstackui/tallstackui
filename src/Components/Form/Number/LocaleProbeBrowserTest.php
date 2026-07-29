<?php

namespace TallStackUi\Components\Form\Number;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class LocaleProbeBrowserTest extends BrowserTestCase
{
    #[Test]
    public function probe_comma_handling_in_number_input(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public float $quantity = 0.0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="quantity">{{ $quantity }}</p>
                    <x-number wire:model="quantity" min="0" step="0.01" />
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Save');

        $env = $browser->script(<<<'JS'
            const input = document.querySelector('[dusk="tallstackui_form_number_input"]');
            return JSON.stringify({
                language: navigator.language,
                languages: navigator.languages,
                inputType: input ? input.getAttribute('type') : null,
                inputMode: input ? input.getAttribute('inputmode') : null,
                decimalFormat: new Intl.NumberFormat().format(1.5),
            });
        JS)[0];

        dump(['environment' => json_decode($env, true)]);

        $browser->type('@tallstackui_form_number_input', '10,5');

        $afterType = $browser->script(<<<'JS'
            const input = document.querySelector('[dusk="tallstackui_form_number_input"]');
            return JSON.stringify({
                value: input.value,
                valueAsNumber: Number.isNaN(input.valueAsNumber) ? 'NaN' : input.valueAsNumber,
                validity: {badInput: input.validity.badInput, valid: input.validity.valid},
            });
        JS)[0];

        dump(['after typing "10,5"' => json_decode($afterType, true)]);

        $browser->click('@sync')->pause(1500);

        dump(['quantity rendered' => $browser->text('@quantity')]);

        $this->assertTrue(true);
    }
}
