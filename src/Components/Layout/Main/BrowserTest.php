<?php

namespace TallStackUi\Components\Layout\Main;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function does_not_indent_the_content_without_a_menu(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:header>
                            <x-layout.header />
                        </x-slot:header>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(function (Browser $browser): void {
                Assert::assertSame('0px', $this->indentation($browser), 'the content must not be padded for a sidebar that was not given');
            });
    }

    #[Test]
    public function keeps_the_footer_beside_the_sidebar(): void
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
                        <x-slot:footer>
                            <footer id="footer">Footer</footer>
                        </x-slot:footer>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        })
            ->resize(1400, 900)
            ->waitForText('Footer')
            ->tap(function (Browser $browser): void {
                $footer = $browser->script('return document.querySelector("#footer").getBoundingClientRect().left')[0];

                Assert::assertGreaterThanOrEqual(288, $footer, 'the footer must start past the sidebar instead of under it');
            });
    }

    #[Test]
    public function locks_the_body_while_the_mobile_drawer_is_open(): void
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
            ->tap(function (Browser $browser): void {
                Assert::assertSame('hidden', $this->overflow($browser), 'the body must be locked while the drawer is open');
                Assert::assertSame('side-bar', $this->marker($browser), 'the sidebar must own the lock it took');
            })
            ->tap(fn (Browser $browser) => $browser->script("window.dispatchEvent(new CustomEvent('tallstackui-menu-mobile', { detail: { status: false } }))"))
            ->pause(500)
            ->tap(function (Browser $browser): void {
                Assert::assertNotSame('hidden', $this->overflow($browser), 'the body must be released when the drawer closes');
                Assert::assertNull($this->marker($browser), 'the lock marker must be removed with the lock');
            });
    }

    private function indentation(Browser $browser): string
    {
        return $browser->script('return getComputedStyle(document.querySelector("main").parentElement).paddingLeft')[0];
    }

    private function marker(Browser $browser): ?string
    {
        return $browser->script('return document.body.getAttribute("data-overflow")')[0];
    }

    private function overflow(Browser $browser): string
    {
        return $browser->script('return document.body.style.overflow')[0];
    }
}
