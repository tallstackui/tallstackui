<?php

namespace TallStackUi\Components\Dropdown;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_render_with_action(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown>
                        <x-slot:action>
                            <x-button id="action" x-on:click="show = !show">
                                FooBar
                            </x-button>
                        </x-slot:action>
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
            HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('#action')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->click('#action')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    #[Test]
    public function can_render_with_icon(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-dropdown icon="chevron-down">
                            <x-dropdown.items text="Lorem" />
                            <x-dropdown.items text="Ipsum" />
                        </x-dropdown>
                    </div>
                HTML;
            }
        })
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->assertSee('Lorem')
            ->assertSee('Ipsum')
            ->click('@tallstackui_open_dropdown')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    #[Test]
    public function can_render_with_submenu(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="FooBar">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                        <x-dropdown.submenu text="Submenu">
                            <x-dropdown.items text="Item 1" />
                            <x-dropdown.items text="Item 2" />
                        </x-dropdown.submenu>
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->clickAtXPath('//button[contains(., "Submenu")]')
            ->waitForText('Item 1')
            ->assertSee('Item 1')
            ->keys('', '{escape}')
            ->waitUntilMissingText('Item 1')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    #[Test]
    public function can_render_with_title(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="FooBar">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->click('@tallstackui_open_dropdown')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum');
    }

    #[Test]
    public function can_use_open_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public bool $opened = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($opened)
                        <p dusk="opened">Opened</p>
                    @endif
                
                    <x-dropdown text="FooBar" x-on:open="$wire.set('opened', true)">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Opened')
            ->assertSee('Opened')
            ->assertVisible('@opened');
    }

    #[Test]
    public function can_use_select_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public bool $selected = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($selected)
                        <p dusk="selected">Selected</p>
                    @endif
                
                    <x-dropdown text="FooBar" x-on:select="$wire.set('selected', true)">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@tallstackui_open_dropdown')
            ->clickAtXPath('//button[contains(., "Lorem")]')
            ->waitForText('Selected')
            ->assertSee('Selected')
            ->assertVisible('@selected');
    }

    #[Test]
    public function can_use_select_event_to_close_dropdown(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dropdown text="FooBar" x-on:select="show = false">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.items text="Ipsum" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('FooBar')
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->waitForText('Ipsum')
            ->assertSee('Lorem')
            ->assertSee('Ipsum')
            ->clickAtXPath('//button[contains(., "Lorem")]')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum')
            ->assertDontSee('Lorem')
            ->assertDontSee('Ipsum');
    }
}
