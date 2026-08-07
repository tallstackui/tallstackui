<?php

namespace TallStackUi\Components\Swap;

use Facebook\WebDriver\WebDriverBy;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_emit_swap_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $logged = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($logged)
                        <p dusk="logged">{{ $logged }}</p>
                    @endif

                    <x-swap :options="['Apple', 'Banana']"
                            x-on:swap="$wire.set('logged', $event.detail.value + ':' + $event.detail.direction)" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@logged', 'Banana:next')
            ->assertSeeIn('@logged', 'Banana:next');
    }

    #[Test]
    public function can_navigate_by_dragging(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple');

        $browser->driver->action()
            ->clickAndHold($browser->driver->findElement(WebDriverBy::cssSelector('[dusk="tallstackui_swap_viewport"]')))
            ->moveByOffset(-90, 0)
            ->release()
            ->perform();

        $browser->waitForTextIn('@fruit', 'Banana');
    }

    #[Test]
    public function can_navigate_when_vertical(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" vertical :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Banana');
    }

    #[Test]
    public function can_navigate_with_buttons(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Banana')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Cherry')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_prev')
            ->waitForTextIn('@fruit', 'Cherry');
    }

    #[Test]
    public function can_navigate_with_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Banana')
            ->keys('@tallstackui_swap_next', '{arrow_right}')
            ->waitForTextIn('@fruit', 'Cherry')
            ->keys('@tallstackui_swap_prev', '{arrow_left}')
            ->waitForTextIn('@fruit', 'Banana');
    }

    #[Test]
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-swap :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->waitForText('Apple')
            ->assertSee('Apple');
    }

    #[Test]
    public function can_start_from_wire_model_value(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Cherry';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Cherry')
            ->click('@tallstackui_swap_prev')
            ->waitForTextIn('@fruit', 'Banana');
    }

    #[Test]
    public function cannot_navigate_beyond_the_edges_without_loop(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" :loop="false" :options="['Apple', 'Banana', 'Cherry']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_prev')
            ->pause(500)
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Banana')
            ->click('@tallstackui_swap_next')
            ->waitForTextIn('@fruit', 'Cherry')
            ->click('@tallstackui_swap_next')
            ->pause(500)
            ->assertSeeIn('@fruit', 'Cherry');
    }

    #[Test]
    public function cannot_navigate_when_disabled(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" disabled :options="['Apple', 'Banana']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->pause(500)
            ->assertSeeIn('@fruit', 'Apple');
    }

    #[Test]
    public function cannot_navigate_when_readonly(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $fruit = 'Apple';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="fruit">{{ $fruit }}</p>

                    <x-swap wire:model.live="fruit" readonly :options="['Apple', 'Banana']" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertSeeIn('@fruit', 'Apple')
            ->click('@tallstackui_swap_next')
            ->pause(500)
            ->assertSeeIn('@fruit', 'Apple');
    }
}
