<?php

namespace TallStackUi\Components\Dial;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_close_on_click_outside(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="outside">Outside</p>
                    <x-dial>
                        <x-dial.items icon="pencil" label="Edit" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item')
            ->click('@outside')
            ->waitUntilMissing('@tallstackui_dial_item');
    }

    #[Test]
    public function can_close_on_escape(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial>
                        <x-dial.items icon="pencil" label="Edit" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item')
            ->keys('', '{escape}')
            ->waitUntilMissing('@tallstackui_dial_item');
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
                    <x-dial>
                        <x-dial.items icon="pencil" />
                        <x-dial.items icon="trash" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_dial_toggle');
    }

    #[Test]
    public function can_render_as_link(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial>
                        <x-dial.items icon="pencil" href="/edit" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertAttributeContains('@tallstackui_dial_item', 'href', '/edit');
    }

    #[Test]
    public function can_render_at_different_positions(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial position="top-left">
                        <x-dial.items icon="pencil" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_dial_toggle')
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item');
    }

    #[Test]
    public function can_render_horizontal(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial horizontal>
                        <x-dial.items icon="pencil" label="Edit" />
                        <x-dial.items icon="trash" label="Delete" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertSee('Edit')
            ->assertSee('Delete');
    }

    #[Test]
    public function can_render_with_custom_color(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial color="red">
                        <x-dial.items icon="pencil" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_dial_toggle')
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item');
    }

    #[Test]
    public function can_render_with_custom_icon(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial icon="bars-4">
                        <x-dial.items icon="pencil" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_dial_toggle')
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item');
    }

    #[Test]
    public function can_render_with_labels(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial>
                        <x-dial.items icon="pencil" label="Edit" />
                        <x-dial.items icon="share" label="Share" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_dial_toggle')
            ->waitForText('Edit')
            ->assertSee('Edit')
            ->assertSee('Share');
    }

    #[Test]
    public function can_render_with_square_shape(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial square>
                        <x-dial.items icon="pencil" square />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertPresent('@tallstackui_dial_toggle')
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item');
    }

    #[Test]
    public function can_toggle_on_click(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-dial>
                        <x-dial.items icon="pencil" label="Edit" />
                        <x-dial.items icon="trash" label="Delete" />
                    </x-dial>
                </div>
                HTML;
            }
        })
            ->assertMissing('@tallstackui_dial_item')
            ->click('@tallstackui_dial_toggle')
            ->waitFor('@tallstackui_dial_item')
            ->assertVisible('@tallstackui_dial_item')
            ->assertSee('Edit')
            ->assertSee('Delete')
            ->click('@tallstackui_dial_toggle')
            ->waitUntilMissing('@tallstackui_dial_item');
    }
}
