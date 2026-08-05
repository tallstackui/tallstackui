<?php

namespace TallStackUi\Components\Layout\SideBar\Main;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function closes_the_mobile_drawer_on_escape(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:menu>
                            <x-side-bar>
                                <x-side-bar.item text="Home" icon="home" href="#" />
                            </x-side-bar>
                        </x-slot:menu>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(480, 700)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("window.dispatchEvent(new CustomEvent('tallstackui-menu-mobile', { detail: { status: true } }))"))
            ->pause(500)
            ->tap(fn (Browser $browser) => $browser->script("window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }))"))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                Assert::assertNotSame('hidden', $browser->script('return document.body.style.overflow')[0], 'escape must close the drawer, which releases the scroll lock with it');
            });
    }

    #[Test]
    public function does_not_collapse_after_leaving_the_mobile_viewport(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:menu>
                            <x-side-bar collapsible>
                                <x-side-bar.item text="Home" icon="home" href="#" />
                            </x-side-bar>
                        </x-slot:menu>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(480, 700)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("localStorage.removeItem('side-bar')"))
            ->refresh()
            ->waitForText('Content')
            ->resize(1400, 900)
            ->pause(600)
            ->tap(function (Browser $browser): void {
                Assert::assertSame('288px', $this->indentation($browser), 'a sidebar nobody collapsed must not come back from mobile as a rail');
            });
    }

    #[Test]
    public function does_not_overwrite_the_collapse_preference_when_the_drawer_opens(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:menu>
                            <x-side-bar collapsible>
                                <x-side-bar.item text="Home" icon="home" href="#" />
                            </x-side-bar>
                        </x-slot:menu>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(false)"))
            ->pause(400)
            ->tap(fn (Browser $browser) => $browser->script("window.dispatchEvent(new CustomEvent('tallstackui-menu-mobile', { detail: { status: true } }))"))
            ->pause(400)
            ->tap(function (Browser $browser): void {
                Assert::assertSame('false', $browser->script("return localStorage.getItem('side-bar')")[0], 'the drawer must not write over the desktop preference');
            });
    }

    #[Test]
    public function keeps_the_width_of_a_sidebar_that_does_not_collapse(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:menu>
                            <x-side-bar>
                                <x-side-bar.item text="Home" icon="home" href="#" />
                            </x-side-bar>
                        </x-slot:menu>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("localStorage.setItem('side-bar', 'false')"))
            ->refresh()
            ->waitForText('Content')
            ->pause(600)
            ->tap(function (Browser $browser): void {
                Assert::assertSame('288px', $this->indentation($browser), 'a sidebar without collapsible must ignore the persisted collapse state');
            });
    }

    private function indentation(Browser $browser): string
    {
        return $browser->script('return getComputedStyle(document.querySelector("main").parentElement).paddingLeft')[0];
    }
}
