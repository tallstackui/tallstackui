<?php

namespace TallStackUi\Components\Form\Autocomplete;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class SelectEventCollisionBrowserTest extends BrowserTestCase
{
    #[Test]
    public function clicking_an_item_invokes_user_x_on_select_handler_with_custom_event_detail(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="result"></p>
                    <x-autocomplete :items="[
                        ['value' => 'Alice', 'description' => 'admin'],
                        ['value' => 'Bob', 'description' => 'editor'],
                    ]" x-on:select="document.querySelector('[dusk=result]').textContent = $event.detail.item.value" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Alice')
            ->pause(150)
            ->click('@tallstackui_autocomplete_option')
            ->pause(200)
            ->waitForTextIn('@result', 'Alice');
    }

    #[Test]
    public function native_input_select_event_does_not_invoke_user_x_on_select_handler(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'Alice', 'description' => 'admin'],
                        ['value' => 'Bob', 'description' => 'editor'],
                    ]" x-on:select="
                        window.__tsuiAutocompleteCollision = window.__tsuiAutocompleteCollision || { invocations: 0, errors: [] };
                        window.__tsuiAutocompleteCollision.invocations++;
                        try {
                            void $event.detail.item.value;
                        } catch (error) {
                            window.__tsuiAutocompleteCollision.errors.push(error.message);
                        }
                    " />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Alice')
            ->pause(150)
            ->tap(fn ($browser) => $browser->script(<<<'JS'
                window.__tsuiAutocompleteCollision = { invocations: 0, errors: [] };

                const input = document.querySelector('input[dusk=tallstackui_autocomplete_input]');
                input.value = 'Alice';
                input.focus();
                input.setSelectionRange(0, 5);
                input.dispatchEvent(new Event('select', { bubbles: true }));
            JS))
            ->pause(200)
            ->tap(function ($browser): void {
                $state = $browser->script('return JSON.stringify(window.__tsuiAutocompleteCollision)')[0];
                $payload = json_decode($state, true);

                $this->assertSame(0, $payload['invocations'], 'Native input "select" event must not invoke the user x-on:select handler.');
                $this->assertSame([], $payload['errors'], 'Native input "select" event must not produce JavaScript errors.');
            });
    }
}
