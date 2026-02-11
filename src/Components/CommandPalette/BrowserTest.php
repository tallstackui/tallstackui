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
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
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
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette');

        $browser->script('$tsui.close.commandPalette()');

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
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
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
    public function can_open_and_close_with_keyboard_shortcut(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                </div>
                HTML;
            }
        });

        $browser->assertMissing('@tallstackui_command_palette')
            ->keys('', ['{control}', 'k'])
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette')
            ->keys('', ['{control}', 'k'])
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
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertMissing('@tallstackui_command_palette')
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette');
    }

    #[Test]
    public function cannot_close_by_clicking_outside_when_persistent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                config()->set('ts-ui.components.command-palette', [
                    \TallStackUi\Components\CommandPalette\Component::class,
                    [
                        'request' => null,
                        'z-index' => 'z-50',
                        'blur' => false,
                        'overflow' => false,
                        'shortcut' => 'ctrl.k',
                        'persistent' => true,
                        'elements' => true,
                        'scrollbar' => null,
                    ],
                ]);

                __ts_get_component_configuration(\TallStackUi\Components\CommandPalette\Component::class, flush: true);

                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertVisible('@tallstackui_command_palette')
            ->clickAtPoint(10, 10)
            ->pause(500)
            ->assertVisible('@tallstackui_command_palette');
    }

    #[Test]
    public function focuses_search_input_on_open(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->assertFocused('@tallstackui_command_palette_search');
    }
}
