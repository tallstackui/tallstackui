<?php

namespace TallStackUi\Components\Form\Autocomplete;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_filter_by_description(): void
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
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Alice')
            ->type('@tallstackui_autocomplete_input', 'editor')
            ->pause(300)
            ->assertSee('Bob')
            ->assertDontSee('Alice');
    }

    #[Test]
    public function can_filter_by_value(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                        ['value' => 'Baz'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->type('@tallstackui_autocomplete_input', 'Ba')
            ->pause(300)
            ->assertSee('Bar')
            ->assertSee('Baz')
            ->assertDontSee('Foo');
    }

    #[Test]
    public function can_navigate_with_arrow_keys_and_pick_with_enter(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete wire:model.live="picked" :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                        ['value' => 'Baz'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->pause(150)
            ->keys('@tallstackui_autocomplete_input', ['{ARROW_DOWN}'])
            ->pause(50)
            ->keys('@tallstackui_autocomplete_input', ['{ARROW_DOWN}'])
            ->pause(50)
            ->keys('@tallstackui_autocomplete_input', ['{ENTER}'])
            ->waitForTextIn('@picked', 'Bar');
    }

    #[Test]
    public function can_open_dropdown_on_focus_and_show_all_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                        ['value' => 'Baz'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->assertSee('Baz');
    }

    #[Test]
    public function can_pick_item_with_click_and_dispatches_select_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete wire:model.live="picked" :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                    ]" x-on:select="$wire.set('picked', $event.detail.item.value)" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->pause(150)
            ->click('@tallstackui_autocomplete_option')
            ->waitForTextIn('@picked', 'Foo');
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
                    <x-autocomplete :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                        ['value' => 'Baz'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->assertVisible('@tallstackui_autocomplete_input');
    }

    #[Test]
    public function can_render_items_with_remapped_keys(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['name' => 'Alice', 'email' => 'alice@example.com'],
                        ['name' => 'Bob', 'email' => 'bob@example.com'],
                    ]" select="value:name|description:email" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Alice')
            ->assertSee('alice@example.com')
            ->type('@tallstackui_autocomplete_input', 'bob@')
            ->pause(300)
            ->assertSee('Bob')
            ->assertDontSee('Alice');
    }

    #[Test]
    public function clearable_button_resets_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete wire:model.live="picked" clearable :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->pause(150)
            ->click('@tallstackui_autocomplete_option')
            ->waitForTextIn('@picked', 'Foo')
            ->click('@tallstackui_autocomplete_clear')
            ->pause(150)
            ->assertInputValue('@tallstackui_autocomplete_input', '');
    }

    #[Test]
    public function metadata_defaults_to_null_when_the_item_omits_it(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete :items="[
                        ['value' => 'Bob'],
                    ]" x-on:select="$wire.set('picked', JSON.stringify($event.detail.item.metadata))" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Bob')
            ->pause(150)
            ->click('@tallstackui_autocomplete_option')
            ->waitForTextIn('@picked', 'null');
    }

    #[Test]
    public function metadata_of_local_items_reaches_the_select_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete :items="[
                        ['value' => 'Alice', 'metadata' => ['id' => 42, 'role' => 'admin']],
                    ]" x-on:select="$wire.set('picked', $event.detail.item.metadata.role + ':' + $event.detail.item.metadata.id)" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Alice')
            ->pause(150)
            ->click('@tallstackui_autocomplete_option')
            ->waitForTextIn('@picked', 'admin:42');
    }

    #[Test]
    public function metadata_survives_a_remote_request(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete request="/searchable-with-metadata"
                                    x-on:select="$wire.set('picked', $event.detail.item.metadata.role + ':' + $event.detail.item.metadata.id)" />
                </div>
                HTML;
            }
        })
            ->type('@tallstackui_autocomplete_input', 'Alice')
            ->waitForText('Alice')
            ->pause(300)
            ->click('@tallstackui_autocomplete_option')
            ->waitForTextIn('@picked', 'admin:42');
    }

    #[Test]
    public function shows_after_slot_when_provided_and_empty(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[['value' => 'Foo']]">
                        <x-slot:after>
                            <p dusk="custom-after">Nothing here. Create one?</p>
                        </x-slot:after>
                    </x-autocomplete>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->type('@tallstackui_autocomplete_input', 'xyz')
            ->pause(300)
            ->assertVisible('@custom-after');
    }

    #[Test]
    public function shows_default_empty_message_when_no_match(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'Foo'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->type('@tallstackui_autocomplete_input', 'xyz')
            ->pause(300)
            ->assertSee('No results found');
    }

    #[Test]
    public function strict_reverts_input_on_blur_when_unmatched(): void
    {
        Livewire::visit(new class extends Component
        {
            public string $picked = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked }}</p>
                    <x-autocomplete wire:model.live="picked" strict :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                    ]" />
                    <button type="button" dusk="elsewhere">Click outside</button>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Foo')
            ->pause(150)
            ->type('@tallstackui_autocomplete_input', 'unmatched')
            ->pause(300)
            ->keys('@tallstackui_autocomplete_input', ['{ESCAPE}'])
            ->pause(300)
            ->assertInputValue('@tallstackui_autocomplete_input', '');
    }
}
