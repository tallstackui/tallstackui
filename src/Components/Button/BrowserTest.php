<?php

namespace TallStackUi\Components\Button;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_focus_button_with_unfocus_through_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="focusable" text="Focusable" />

                    <x-button dusk="unfocusable" unfocus text="Unfocusable" />
                </div>
                HTML;
            }
        })
            ->click('@focusable')
            ->keys('@focusable', ['{tab}'])
            ->assertScript('document.activeElement.getAttribute("dusk")', 'unfocusable');
    }

    #[Test]
    public function can_see_loading_spinner_with_circle_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input id="input" wire:model="foo" />
                
                    <x-button.circle dusk="sync" loading="sync" wire:click="sync" text="Save" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                sleep(1);

                // ...
            }
        })
            ->assertDontSee('svg')
            ->type('input', 'Foo bar')
            ->click('@sync')
            ->waitFor('@button-loading-spinner');
    }

    #[Test]
    public function can_see_loading_spinner_with_normal_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input id="input" wire:model="foo" />
                
                    <x-button dusk="sync" loading="sync" wire:click="sync" text="Save" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                sleep(1);

                // ...
            }
        })
            ->assertDontSee('svg')
            ->type('input', 'Foo bar')
            ->click('@sync')
            ->waitFor('@button-loading-spinner');
    }

    #[Test]
    public function cannot_focus_button_with_unfocus_through_mouse(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-button dusk="focusable" text="Focusable" />

                    <x-button dusk="unfocusable" unfocus text="Unfocusable" />
                </div>
                HTML;
            }
        })
            ->click('@focusable')
            ->assertScript('document.activeElement.getAttribute("dusk")', 'focusable')
            ->click('@unfocusable')
            ->assertScript('document.activeElement.getAttribute("dusk")', 'focusable');
    }
}
