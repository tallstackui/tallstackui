<?php

namespace TallStackUi\Components\List;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function clearing_search_restores_all_rows(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list searchable>
                        <x-list.items name="general" />
                        <x-list.items name="production" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->type('@tallstackui_list_search', 'prod')
            ->pause(300)
            ->waitUntilMissingText('general')
            ->keys('@tallstackui_list_search', '{backspace}', '{backspace}', '{backspace}', '{backspace}')
            ->pause(300)
            ->waitForText('general')
            ->assertSee('general')
            ->assertSee('production');
    }

    #[Test]
    public function clicking_ellipsis_opens_dropdown_menu(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list>
                        <x-list.items name="general">
                            <x-slot:menu>
                                <x-dropdown.items text="Edit me" />
                                <x-dropdown.items text="Delete me" />
                            </x-slot:menu>
                        </x-list.items>
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->assertDontSee('Edit me')
            ->click('@tallstackui_list_items_menu')
            ->waitForText('Edit me')
            ->assertSee('Edit me')
            ->assertSee('Delete me');
    }

    #[Test]
    public function data_driven_menu_opens_for_specific_item(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $items = [
                ['name' => 'general', 'id' => 1],
                ['name' => 'production', 'id' => 2],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list :items="$items">
                        @interact('item_menu', $item)
                            <x-dropdown.items text="Action {{ $item['id'] }}" />
                        @endinteract
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->click('@tallstackui_list_items_menu')
            ->waitForText('Action 1')
            ->assertSee('Action 1');
    }

    #[Test]
    public function data_driven_mode_renders_items_from_array(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $items = [
                ['name' => 'general', 'caption' => '1 server'],
                ['name' => 'production', 'caption' => '2 servers'],
            ];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list :items="$items" />
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->assertSee('general')
            ->assertSee('production');
    }

    #[Test]
    public function height_prop_applies_max_height_class(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list height="60">
                        <x-list.items name="alpha" />
                        <x-list.items name="bravo" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('alpha')
            ->assertPresent('.max-h-60.overflow-y-auto');
    }

    #[Test]
    public function search_filters_rows_by_caption(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list searchable>
                        <x-list.items name="general" caption="primary cluster" />
                        <x-list.items name="production" caption="staging cluster" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->assertSee('general')
            ->type('@tallstackui_list_search', 'staging')
            ->pause(300)
            ->waitUntilMissingText('general')
            ->assertSee('production');
    }

    #[Test]
    public function search_filters_rows_by_name(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list searchable>
                        <x-list.items name="general" caption="1 server" />
                        <x-list.items name="production" caption="2 servers" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->assertSee('general')
            ->assertSee('production')
            ->type('@tallstackui_list_search', 'prod')
            ->pause(300)
            ->waitUntilMissingText('general')
            ->assertSee('production');
    }

    #[Test]
    public function search_is_case_insensitive(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list searchable>
                        <x-list.items name="General" />
                        <x-list.items name="Production" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('General')
            ->type('@tallstackui_list_search', 'GENE')
            ->pause(300)
            ->assertSee('General')
            ->waitUntilMissingText('Production');
    }

    #[Test]
    public function shows_custom_empty_slot_when_no_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list>
                        <x-slot:empty>
                            <p>No tags configured yet.</p>
                        </x-slot:empty>
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('No tags configured yet.')
            ->assertSee('No tags configured yet.')
            ->assertDontSee('No items.');
    }

    #[Test]
    public function shows_default_empty_state_when_no_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list />
                </div>
                HTML;
            }
        })
            ->waitForText('No items.')
            ->assertSee('No items.');
    }

    #[Test]
    public function shows_empty_state_when_search_filters_all_rows(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-list searchable>
                        <x-list.items name="general" />
                        <x-list.items name="production" />
                    </x-list>
                </div>
                HTML;
            }
        })
            ->waitForText('general')
            ->type('@tallstackui_list_search', 'zzznotmatch')
            ->pause(300)
            ->waitForText('No items.')
            ->assertSee('No items.');
    }
}
