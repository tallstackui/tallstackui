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
    public function can_dispatch_close_lifecycle_event(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ closed: false }" x-on:command-palette:close.window="closed = true">
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span x-show="closed" dusk="closed">Closed</span>
                </div>
                HTML;
            }
        });

        $browser->assertMissing('@closed')
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->keys('', '{escape}')
            ->waitFor('@closed')
            ->assertVisible('@closed');
    }

    #[Test]
    public function can_dispatch_global_event_as_fallback(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ selected: '' }" x-on:command-palette:select.window="selected = $event.detail.label">
                    <x-command-palette request="/searchable-filtered" select="label:label|value:value" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="global-result" x-text="selected"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->type('@tallstackui_command_palette_search', 'et')
            ->waitForTextIn('@tallstackui_command_palette', 'et porro tempora')
            ->click('[role="option"]')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->waitForTextIn('@global-result', 'et porro tempora')
            ->assertSeeIn('@global-result', 'et porro tempora');
    }

    #[Test]
    public function can_dispatch_inline_close_event(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette
                        request="https://example.com/search"
                        select="label:title|value:id"
                        x-on:close="$refs.inlineClosed.textContent = 'inline-closed'" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="inline-closed" x-ref="inlineClosed"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->keys('', '{escape}')
            ->waitForTextIn('@inline-closed', 'inline-closed')
            ->assertSeeIn('@inline-closed', 'inline-closed');
    }

    #[Test]
    public function can_dispatch_inline_open_event(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette
                        request="https://example.com/search"
                        select="label:title|value:id"
                        x-on:open="$refs.inlineOpened.textContent = 'inline-opened'" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="inline-opened" x-ref="inlineOpened"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->waitForTextIn('@inline-opened', 'inline-opened')
            ->assertSeeIn('@inline-opened', 'inline-opened');
    }

    #[Test]
    public function can_dispatch_open_lifecycle_event(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ opened: false }" x-on:command-palette:open.window="opened = true">
                    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span x-show="opened" dusk="opened">Opened</span>
                </div>
                HTML;
            }
        });

        $browser->assertMissing('@opened')
            ->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->waitFor('@opened')
            ->assertVisible('@opened');
    }

    #[Test]
    public function can_dispatch_select_event_on_inline_click(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette
                        request="/searchable-filtered"
                        select="label:label|value:value"
                        x-on:select="$refs.result.textContent = JSON.stringify($event.detail)" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="result" x-ref="result"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->type('@tallstackui_command_palette_search', 'et')
            ->waitForTextIn('@tallstackui_command_palette', 'et porro tempora')
            ->click('[role="option"]')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->assertSeeIn('@result', 'et porro tempora');
    }

    #[Test]
    public function can_dispatch_select_event_via_keyboard_enter(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-command-palette
                        request="/searchable-filtered"
                        select="label:label|value:value"
                        x-on:select="$refs.result.textContent = $event.detail.label" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="result" x-ref="result"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->type('@tallstackui_command_palette_search', 'et')
            ->waitForTextIn('@tallstackui_command_palette', 'et porro tempora')
            ->keys('@tallstackui_command_palette_search', '{arrow_down}', '{enter}')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->assertSeeIn('@result', 'et porro tempora');
    }

    #[Test]
    public function can_execute_actionable_event_callback(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ actionExecuted: false }" x-on:action-executed.window="actionExecuted = true">
                    <x-command-palette request="/searchable-filtered" select="label:label|value:value" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span x-show="actionExecuted" dusk="action-executed">Action Executed</span>
                </div>
                HTML;
            }
        });

        $browser->script("
            const el = document.querySelector('[x-data*=\"tallstackui_commandPalette\"]');
            Alpine.\$data(el)._url = '/mock-command-palette-action';
        ");

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->type('@tallstackui_command_palette_search', 'et')
            ->waitForTextIn('@tallstackui_command_palette', 'et porro tempora')
            ->click('[role="option"]')
            ->waitFor('@action-executed')
            ->assertVisible('@action-executed');
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
                        'actionable' => null,
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

    #[Test]
    public function inline_select_takes_priority_over_global_event(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{ inlineResult: '', globalResult: '' }" x-on:command-palette:select.window="globalResult = 'global-fired'">
                    <x-command-palette
                        request="/searchable-filtered"
                        select="label:label|value:value"
                        x-on:select="inlineResult = $event.detail.label" />
                    <x-button dusk="open" x-on:click="$tsui.open.commandPalette()">Open</x-button>
                    <span dusk="inline-result" x-text="inlineResult"></span>
                    <span dusk="global-result" x-text="globalResult"></span>
                </div>
                HTML;
            }
        });

        $browser->click('@open')
            ->waitFor('@tallstackui_command_palette')
            ->type('@tallstackui_command_palette_search', 'et')
            ->waitForTextIn('@tallstackui_command_palette', 'et porro tempora')
            ->click('[role="option"]')
            ->waitUntilMissing('@tallstackui_command_palette')
            ->waitForTextIn('@inline-result', 'et porro tempora')
            ->assertSeeIn('@inline-result', 'et porro tempora')
            ->assertSeeNothingIn('@global-result');
    }
}
