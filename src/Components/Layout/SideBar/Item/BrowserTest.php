<?php

namespace TallStackUi\Components\Layout\SideBar\Item;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function centers_the_icon_of_an_item_with_badge_when_collapsed(): void
    {
        Livewire::visit($this->sidebar())
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(false)"))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                $offset = $browser->script(<<<'JS'
                    const link = Array.from(document.querySelectorAll('a')).find((element) => element.offsetParent !== null && element.textContent.includes('Users'));
                    const icon = link.querySelector('svg');

                    const linkBox = link.getBoundingClientRect();
                    const iconBox = icon.getBoundingClientRect();

                    return Math.abs((iconBox.left + iconBox.right) / 2 - (linkBox.left + linkBox.right) / 2);
                JS)[0];

                Assert::assertLessThanOrEqual(1, $offset, 'the icon of a collapsed item must sit on the center, with the label and the badge out of the layout');
            });
    }

    #[Test]
    public function degrades_the_badge_to_a_dot_when_collapsed(): void
    {
        Livewire::visit($this->sidebar())
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(true)"))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                $badging = $this->badging($browser);

                Assert::assertGreaterThan(0, $badging['badge'], 'an expanded item shows the badge itself');
                Assert::assertSame(0, $badging['dot'], 'an expanded item has no use for the dot');
            })
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(false)"))
            ->pause(800)
            ->tap(function (Browser $browser): void {
                $badging = $this->badging($browser);

                Assert::assertSame(0, $badging['badge'], 'a collapsed item has no room for the badge');
                Assert::assertGreaterThan(0, $badging['dot'], 'the dot has to carry the badge the collapsed item cannot show');
            });
    }

    #[Test]
    public function does_not_open_the_flyout_of_a_group_when_expanded(): void
    {
        Livewire::visit($this->sidebar())
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(true)"))
            ->pause(600)
            ->tap(fn (Browser $browser) => $browser->script($this->hover()))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                Assert::assertNull($this->panel($browser), 'an expanded sidebar opens its groups inline, never in a floating panel');
            });
    }

    #[Test]
    public function draws_the_tree_lines_of_a_group_down_to_its_last_item(): void
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
                                <x-side-bar.item text="Users" icon="users" href="#" />
                                <x-side-bar.item text="Settings" icon="cog-6-tooth" opened>
                                    <x-side-bar.item text="General" href="#" />
                                    <x-side-bar.item text="Advanced" opened>
                                        <x-side-bar.item text="Cache" href="#" />
                                    </x-side-bar.item>
                                    <x-side-bar.item text="Privacy" href="#" />
                                </x-side-bar.item>
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
            ->pause(400)
            ->tap(function (Browser $browser): void {
                $lines = $browser->script(<<<'JS'
                    const visible = (tag, text) => Array.from(document.querySelectorAll(tag)).find((element) => element.offsetParent !== null && element.textContent.trim() === text);
                    const width = (element, pseudo, side) => parseFloat(getComputedStyle(element, pseudo)[side]) || 0;

                    const line = (text) => {
                        const item = visible('a', text).closest('li');

                        return { branch: width(item, '::before', 'borderBottomWidth'), trunk: width(item, '::after', 'borderLeftWidth') };
                    };

                    const group = visible('button', 'Advanced');

                    return {
                        top: line('Users'),
                        first: line('General'),
                        last: line('Privacy'),
                        nested: { branch: width(group, '::before', 'borderBottomWidth'), trunk: width(group.closest('li'), '::after', 'borderLeftWidth') },
                        only: line('Cache'),
                    };
                JS)[0];

                Assert::assertSame(['branch' => 0, 'trunk' => 0], $lines['top'], 'an item outside a group has nothing to be connected to');
                Assert::assertSame(['branch' => 1, 'trunk' => 1], $lines['first'], 'an item followed by another one carries its branch and the trunk going down');
                Assert::assertSame(['branch' => 1, 'trunk' => 1], $lines['nested'], 'a group inside a group is connected like any other item');
                Assert::assertSame(['branch' => 1, 'trunk' => 0], $lines['last'], 'the trunk must stop at the branch of the last item');
                Assert::assertSame(['branch' => 1, 'trunk' => 0], $lines['only'], 'the only item of a group is also its last one');
            });
    }

    #[Test]
    public function opens_the_flyout_of_a_group_when_collapsed(): void
    {
        Livewire::visit($this->sidebar())
            ->resize(1400, 900)
            ->waitForText('Content')
            ->tap(fn (Browser $browser) => $browser->script("Alpine.store('tsui.side-bar').toggle(false)"))
            ->pause(600)
            ->tap(fn (Browser $browser) => $browser->script($this->hover()))
            ->pause(600)
            ->tap(function (Browser $browser): void {
                $panel = $this->panel($browser);

                Assert::assertNotNull($panel, 'hovering a collapsed group must open its floating panel');
                Assert::assertStringContainsString('General', $panel, 'the panel must carry the items of the group');
                Assert::assertStringContainsString('Settings', $panel, 'the panel must name the group it belongs to');
            });
    }

    private function badging(Browser $browser): array
    {
        return $browser->script(<<<'JS'
            const link = Array.from(document.querySelectorAll('a')).find((element) => element.offsetParent !== null && element.textContent.includes('Users'));
            const spans = Array.from(link.querySelectorAll('span'));

            // The wrapper comes before the badge it holds, and the dot is the
            // only one carrying no text of its own.
            const badge = spans.find((element) => element.textContent.trim() === '12');
            const dot = spans.find((element) => element.textContent.trim() === '');

            return {
                badge: Math.round(badge.getBoundingClientRect().width),
                dot: Math.round(dot.getBoundingClientRect().width),
            };
        JS)[0];
    }

    private function hover(): string
    {
        return <<<'JS'
            const button = Array.from(document.querySelectorAll('button')).find((element) => element.offsetParent !== null && element.textContent.includes('Settings'));

            button.dispatchEvent(new MouseEvent('mouseenter'));
        JS;
    }

    private function panel(Browser $browser): ?string
    {
        return $browser->script(<<<'JS'
            const panel = Array.from(document.querySelectorAll('[data-floating]')).find((element) => element.offsetParent !== null);

            return panel ? panel.textContent.replace(/\s+/g, ' ').trim() : null;
        JS)[0];
    }

    private function sidebar(): Component
    {
        return new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-layout>
                        <x-slot:menu>
                            <x-side-bar collapsible>
                                <x-side-bar.item text="Users" icon="users" href="#" badge="12" />
                                <x-side-bar.item text="Settings" icon="cog-6-tooth">
                                    <x-side-bar.item text="General" href="#" />
                                </x-side-bar.item>
                            </x-side-bar>
                        </x-slot:menu>
                        Content
                    </x-layout>
                </div>
                HTML;
            }
        };
    }
}
