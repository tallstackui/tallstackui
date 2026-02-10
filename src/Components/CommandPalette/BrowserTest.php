<?php

namespace TallStackUi\Components\CommandPalette;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_close_using_helper(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette />
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
                    <x-command-palette />
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
                    <x-command-palette />
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

    #[Test]
    public function can_search_static_options(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $options = [
                ['label' => 'Settings', 'value' => 'settings'],
                ['label' => 'Profile', 'value' => 'profile'],
                ['label' => 'Logout', 'value' => 'logout'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette :options="$options" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->waitForText('Settings')
            ->assertSee('Settings')
            ->assertSee('Profile')
            ->assertSee('Logout')
            ->type('@tallstackui_command_palette_search', 'Set')
            ->waitUntilMissingText('Profile')
            ->assertSee('Settings')
            ->assertDontSee('Profile')
            ->assertDontSee('Logout');
    }

    #[Test]
    public function can_select_option_and_dispatch_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $selected = '';

            public array $options = [
                ['label' => 'Settings', 'value' => 'settings'],
                ['label' => 'Profile', 'value' => 'profile'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div x-on:tallstackui:command-palette.window="$wire.set('selected', $event.detail.value)">
                    <p dusk="selected">{{ $selected }}</p>
                    <x-command-palette :options="$options" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->waitForText('Settings')
            ->click('[role="option"]')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->waitForTextIn('@selected', 'settings')
            ->assertSeeIn('@selected', 'settings');
    }

    #[Test]
    public function shows_empty_state_when_no_results(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $options = [
                ['label' => 'Settings', 'value' => 'settings'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette :options="$options" />
                    <x-button dusk="open" x-on:click="$commandPaletteOpen()">Open</x-button>
                </div>
                HTML;
            }
        })
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->waitForText('Settings')
            ->type('@tallstackui_command_palette_search', 'nonexistentxyz')
            ->waitForText('No results found.')
            ->assertSee('No results found.');
    }
}
