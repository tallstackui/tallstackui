<?php

namespace TallStackUi\Components\Layout\SideBar\Separator;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function takes_no_height_when_the_sidebar_is_collapsed(): void
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
                                <x-side-bar.separator text="Management" />
                                <x-side-bar.item text="Users" icon="users" href="#" />
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
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(true)"))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                Assert::assertGreaterThan(0, $this->height($browser), 'the separator must take room while the sidebar is expanded');
            })
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(false)"))
            ->pause(800)
            ->tap(function (Browser $browser): void {
                Assert::assertSame(0.0, $this->height($browser), 'a collapsed sidebar must not keep the room of a separator nobody can read');
            });
    }

    private function height(Browser $browser): float
    {
        return (float) $browser->script(<<<'JS'
            const span = Array.from(document.querySelectorAll('span')).find((element) => element.offsetParent !== null && element.textContent.trim() === 'Management');

            return span.parentElement.getBoundingClientRect().height;
        JS)[0];
    }
}
