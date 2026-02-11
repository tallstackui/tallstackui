<?php

namespace TallStackUi\Components\Card;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
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

    #[Test]
    public function can_close_card_with_header_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-card close>
                        <x-slot:header>
                            SlotHeader
                        </x-slot:header>
                        TallStackUi
                    </x-card>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('SlotHeader')
            ->assertSee('TallStackUi')
            ->assertPresent('@tallstackui_card_close')
            ->click('@tallstackui_card_close')
            ->waitUntilMissingText('TallStackUi')
            ->assertDontSee('SlotHeader')
            ->assertDontSee('TallStackUi');
    }

    #[Test]
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

    #[Test]
    public function can_minimize_card_with_header_slot(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-card minimize>
                        <x-slot:header>
                            SlotHeader
                        </x-slot:header>
                        TallStackUi
                    </x-card>
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSee('SlotHeader')
            ->assertSee('TallStackUi')
            ->assertPresent('@tallstackui_card_minimize')
            ->click('@tallstackui_card_minimize')
            ->waitUntilMissingText('TallStackUi')
            ->assertSee('SlotHeader')
            ->assertDontSee('TallStackUi');
    }

    #[Test]
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
