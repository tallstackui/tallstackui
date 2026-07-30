<?php

namespace TallStackUi\Components\Toast;

use Illuminate\Config\Repository;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use TallStackUi\Traits\Interactions;
use Tests\Browser\BrowserTestCase;

class StackedBrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_expand_the_pile_on_hover_and_collapse_it_on_leave(): void
    {
        Livewire::visit($this->persistent())
            ->click('@send')
            ->waitForText('Third')
            ->assertScript($this->state('expanded'), false)
            ->mouseover('@tallstackui_toast_stack')
            ->pause($this->paused(1))
            ->assertScript($this->state('expanded'), true)
            ->mouseover('@outside')
            ->pause($this->paused(1))
            ->assertScript($this->state('expanded'), false);
    }

    #[Test]
    public function can_hide_the_content_of_the_buried_cards_while_piled(): void
    {
        Livewire::visit($this->persistent())
            ->click('@send')
            ->waitForText('Third')
            // The front card is the last index, and it is the only one that
            // keeps its content while the pile is closed.
            ->assertScript($this->state('content(2)'), 1)
            ->assertScript($this->state('content(1)'), 0)
            ->assertScript($this->state('content(0)'), 0)
            ->mouseover('@tallstackui_toast_stack')
            ->pause($this->paused(1))
            ->assertScript($this->state('content(0)'), 1)
            ->assertScript($this->state('content(1)'), 1);
    }

    #[Test]
    public function can_hold_every_countdown_while_the_pile_is_expanded(): void
    {
        Livewire::visit($this->expiring())
            ->click('@send')
            ->waitForText('Third')
            ->mouseover('@tallstackui_toast_stack')
            ->pause($this->paused(1))
            ->assertScript($this->state('expanded'), true)
            // Well past the 2s timeout: nothing may expire while hovered.
            ->pause($this->paused(5))
            ->assertScript($this->state('toasts.length'), 3)
            ->assertSee('Third');
    }

    #[Test]
    public function can_keep_a_drained_pile_reachable(): void
    {
        Livewire::visit($this->expiring())
            ->click('@send')
            ->waitForText('Third')
            // Emptying the pile under a stationary pointer used to latch the
            // expanded state, which froze the timer of whatever came next.
            ->mouseover('@tallstackui_toast_stack')
            ->pause($this->paused(1))
            ->mouseover('@outside')
            ->waitUntilMissingText('Third', 15)
            ->assertScript($this->state('expanded'), false)
            ->click('@send')
            ->waitForText('Third')
            ->waitUntilMissingText('Third', 15);
    }

    #[Test]
    public function can_resume_every_countdown_after_the_pointer_leaves(): void
    {
        Livewire::visit($this->expiring())
            ->click('@send')
            ->waitForText('Third')
            ->mouseover('@tallstackui_toast_stack')
            ->pause($this->paused(3))
            ->assertScript($this->state('toasts.length'), 3)
            ->mouseover('@outside')
            ->waitUntilMissingText('Third', 15)
            ->assertScript($this->state('toasts.length'), 0);
    }

    /**
     * The Dusk server boots this class again to build the application it serves,
     * so this is where the pile is turned on for the browser. The flush is
     * required: the component configuration is memoized in a static that the
     * service provider has already filled by the time this runs.
     */
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        tap($app['config'], function (Repository $config) {
            $config->set('ts-ui.components.toast.1.stacked', true);
        });

        __ts_get_component_configuration(\TallStackUi\Components\Toast\Component::class, flush: true);
    }

    protected function expiring(): Component
    {
        return new class extends Component
        {
            use Interactions;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="send" wire:click="send">Send</x-button>
                    <p dusk="outside" class="mt-96">Outside</p>
                </div>
                HTML;
            }

            public function send(): void
            {
                foreach (['First', 'Second', 'Third'] as $title) {
                    $this->toast()->timeout(2)->success($title, "The {$title} toast.")->send();
                }
            }
        };
    }

    protected function persistent(): Component
    {
        return new class extends Component
        {
            use Interactions;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="send" wire:click="send">Send</x-button>
                    <p dusk="outside" class="mt-96">Outside</p>
                </div>
                HTML;
            }

            public function send(): void
            {
                foreach (['First', 'Second', 'Third'] as $title) {
                    $this->toast()->persistent()->success($title, "The {$title} toast.")->send();
                }
            }
        };
    }

    protected function state(string $expression): string
    {
        return "return Alpine.\$data(document.querySelector('[dusk=tallstackui_toast_stack]')).{$expression}";
    }
}
