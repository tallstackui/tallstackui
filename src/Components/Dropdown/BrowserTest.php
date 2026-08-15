<?php

namespace TallStackUi\Components\Dropdown;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_chain_the_submenu_panel_over_the_parent(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div class="p-10">
                    <x-dropdown text="FooBar">
                        <x-dropdown.items text="Lorem" />
                        <x-dropdown.submenu text="Submenu">
                            <x-dropdown.items text="Item 1" />
                        </x-dropdown.submenu>
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->resize(1400, 900)
            ->click('@tallstackui_open_dropdown')
            ->waitForText('Lorem')
            ->clickAtVisibleXPath('//button[contains(., "Submenu")]')
            ->waitForText('Item 1')
            ->tap(function (Browser $browser): void {
                $geometry = $browser->script(<<<'JS'
                    const trigger = Array.from(document.querySelectorAll('button')).find((element) => element.textContent.includes('Submenu'));
                    const panel = Array.from(document.querySelectorAll('[data-floating]')).find((element) => element.textContent.includes('Item 1'));
                    const first = Array.from(panel.querySelectorAll('a, button')).find((element) => element.textContent.includes('Item 1'));

                    const button = trigger.getBoundingClientRect();
                    const submenu = panel.getBoundingClientRect();
                    const item = first.getBoundingClientRect();

                    // The text, not the box: the first item grows upwards through a
                    // transparent border so its fill reaches the panel edge, which puts
                    // its box above the row while the content stays on the same line.
                    const inset = (element, box) => {
                        const style = getComputedStyle(element);

                        return box.top + parseFloat(style.borderTopWidth) + parseFloat(style.paddingTop);
                    };

                    return {
                        overlap: button.right - submenu.left,
                        lift: button.top - submenu.top,
                        alignment: inset(first, item) - inset(trigger, button),
                        bleed: item.top - (submenu.top + parseFloat(getComputedStyle(panel).borderTopWidth)),
                    };
                JS)[0];

                Assert::assertSame(24.0, round($geometry['overlap'], 2), 'the submenu panel must sit slightly over the parent panel');
                Assert::assertSame(5.0, round($geometry['lift'], 2), 'the submenu panel must open slightly above the item that opens it');
                Assert::assertSame(0.0, round($geometry['alignment'], 2), 'the first submenu item must sit on the same line as the item that opens it');
                Assert::assertSame(0.0, round($geometry['bleed'], 2), 'the first submenu item must reach the panel edge, so its hover fill leaves no strip behind');
            });
    }

    #[Test]
    public function can_open_and_close_via_hover(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="outside">Outside</p>

                    <x-dropdown text="Menu" hover>
                        <x-dropdown.items text="Settings" />
                    </x-dropdown>
                </div>
                HTML;
            }
        })
            ->assertSee('Menu')
            ->assertDontSee('Settings');

        $browser->driver->action()
            ->moveToElement($browser->driver->findElement(WebDriverBy::cssSelector('[dusk="tallstackui_open_dropdown"]')))
            ->perform();

        $browser->waitForText('Settings');

        $browser->driver->action()
            ->moveToElement($browser->driver->findElement(WebDriverBy::cssSelector('[dusk="outside"]')))
            ->perform();

        $browser->waitUntilMissingText('Settings')
            ->assertDontSee('Settings');
    }

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
            ->clickAtVisibleXPath('//button[contains(., "Submenu")]')
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
            ->clickAtVisibleXPath('//button[contains(., "Lorem")]')
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
            ->clickAtVisibleXPath('//button[contains(., "Lorem")]')
            ->waitUntilMissingText('Lorem')
            ->waitUntilMissingText('Ipsum')
            ->assertDontSee('Lorem')
            ->assertDontSee('Ipsum');
    }
}
