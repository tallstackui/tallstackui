<?php

namespace TallStackUi\Components\CommandPalette;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_close_by_clicking_outside(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette')
            ->clickAtPoint(10, 10)
            ->waitUntilMissing('@tallstackui_command_palette')
            ->assertMissing('@tallstackui_command_palette');
    }

    #[Test]
    public function can_close_using_helper(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette');

        $browser->script('$commandPaletteClose()');

        $browser->waitUntilMissing('@tallstackui_command_palette')
            ->assertMissing('@tallstackui_command_palette');
    }

    #[Test]
    public function can_close_with_escape(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette')
            ->keys('', '{escape}')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->assertMissing('@tallstackui_command_palette');
    }

    #[Test]
    public function can_open_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertMissing('@tallstackui_command_palette')
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette');
    }
}
