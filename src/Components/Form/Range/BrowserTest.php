<?php

namespace TallStackUi\Components\Form\Range;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_increase(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>

                    <x-range wire:model="quantity" />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->setRangeValue(75)))
            ->click('@sync')
            ->waitForTextIn('@increased', '75', 10)
            ->assertSeeIn('@increased', '75');
    }

    #[Test]
    public function can_increase_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>

                    <x-range wire:model.live="quantity" />
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->setRangeValue(75)))
            ->waitForTextIn('@increased', '75', 10)
            ->assertSeeIn('@increased', '75');
    }

    #[Test]
    public function can_move_the_ending_thumb(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="pair">{{ $price[0] }} - {{ $price[1] }}</p>

                    <x-range dual wire:model="price" />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->move('end', 70)))
            ->click('@sync')
            ->waitForTextIn('@pair', '20 - 70', 10)
            ->assertSeeIn('@pair', '20 - 70');
    }

    #[Test]
    public function can_move_the_starting_thumb(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="pair">{{ $price[0] }} - {{ $price[1] }}</p>

                    <x-range dual wire:model="price" />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->move('start', 35)))
            ->click('@sync')
            ->waitForTextIn('@pair', '35 - 80', 10)
            ->assertSeeIn('@pair', '35 - 80');
    }

    #[Test]
    public function can_move_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="pair">{{ $price[0] }} - {{ $price[1] }}</p>

                    <x-range dual wire:model.live="price" />
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->move('end', 55)))
            ->waitForTextIn('@pair', '20 - 55', 10)
            ->assertSeeIn('@pair', '20 - 55');
    }

    #[Test]
    public function can_separate_the_thumbs_when_they_collapse(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-range dual wire:model="price" />
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->move('end', 20)))
            ->pause(300)
            ->tap(function (Browser $browser) {
                $indexes = $browser->script($this->stacking())[0];

                $this->assertGreaterThan($indexes['start'], $indexes['end'], 'The ending thumb must be on top when the pair collapses near the minimum.');
            })
            ->tap(fn (Browser $browser) => $browser->script($this->move('end', 100)))
            ->tap(fn (Browser $browser) => $browser->script($this->move('start', 100)))
            ->pause(300)
            ->tap(function (Browser $browser) {
                $indexes = $browser->script($this->stacking())[0];

                $this->assertGreaterThan($indexes['end'], $indexes['start'], 'The starting thumb must be on top when the pair collapses near the maximum.');
            });
    }

    #[Test]
    public function can_show_the_tooltip_while_dragging(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div style="margin-top: 100px">
                    <x-range dual wire:model="price" tooltip />
                </div>
                HTML;
            }
        })
            ->assertMissing('@tallstackui_form_range_tooltip_start')
            ->tap(fn (Browser $browser) => $browser->script($this->move('start', 45, change: false)))
            ->waitFor('@tallstackui_form_range_tooltip_start')
            ->assertSeeIn('@tallstackui_form_range_tooltip_start', '45');
    }

    #[Test]
    public function cannot_cross_the_thumbs(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="pair">{{ $price[0] }} - {{ $price[1] }}</p>

                    <x-range dual wire:model="price" />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->move('start', 95)))
            ->click('@sync')
            ->waitForTextIn('@pair', '80 - 80', 10)
            ->assertSeeIn('@pair', '80 - 80');
    }

    #[Test]
    public function cannot_move_when_disabled(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-range dual wire:model="price" disabled />
                </div>
                HTML;
            }
        })
            ->assertAttribute('@tallstackui_form_range_start', 'disabled', 'true')
            ->assertAttribute('@tallstackui_form_range_end', 'disabled', 'true');
    }

    #[Test]
    public function cannot_move_when_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $price = [20, 80];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="pair">{{ $price[0] }} - {{ $price[1] }}</p>

                    <x-range dual wire:model="price" readonly />

                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertAttribute('@tallstackui_form_range_start', 'aria-readonly', 'true')
            ->keys('@tallstackui_form_range_start', '{ARROW_RIGHT}', '{ARROW_RIGHT}')
            ->click('@sync')
            ->pause(500)
            ->assertSeeIn('@pair', '20 - 80');
    }

    private function move(string $thumb, int $value, bool $change = true): string
    {
        $events = $change ? "['input', 'change']" : "['input']";

        return <<<JS
            const input = document.querySelector('[dusk="tallstackui_form_range_{$thumb}"]');
            const nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
            nativeSetter.call(input, {$value});
            {$events}.forEach((name) => input.dispatchEvent(new Event(name, { bubbles: true })));
        JS;
    }

    private function setRangeValue(int $value): string
    {
        return <<<JS
            const input = document.querySelector('[dusk="tallstackui_form_range_input"]');
            const nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
            nativeSetter.call(input, {$value});
            input.dispatchEvent(new Event('input', { bubbles: true }));
        JS;
    }

    private function stacking(): string
    {
        return <<<'JS'
            const read = (thumb) => Number(window.getComputedStyle(
                document.querySelector(`[dusk="tallstackui_form_range_${thumb}"]`)
            ).zIndex);

            return { start: read('start'), end: read('end') };
        JS;
    }
}
