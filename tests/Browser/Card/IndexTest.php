<?php

namespace Tests\Browser\Card;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_close_card(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-card header="Minimizable" close>
                        TallStackUi
                    </x-card>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('TallStackUi')
            ->click('@tallstackui_card_close')
            ->waitUntilMissingText('TallStackUi')
            ->assertDontSee('TallStackUi');
    }

    /** @test */
    public function can_minimize_card(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-card header="Minimizable" minimize>
                        TallStackUi
                    </x-card>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('TallStackUi')
            ->click('@tallstackui_card_minimize')
            ->waitUntilMissingText('TallStackUi')
            ->assertDontSee('TallStackUi');
    }

    /** @test */
    public function can_only_see_elements_when_card_has_header(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-card minimize close>
                        TallStackUi
                    </x-card>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertNotPresent('@tallstackui_card_minimize')
            ->assertNotPresent('@tallstackui_card_close');
    }
}
