<?php

namespace TallStackUi\Components\Form\Autocomplete;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Laravel\Dusk\Browser;
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
    public function can_filter_ignoring_accents_in_the_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'São Paulo'],
                        ['value' => 'Santos'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Santos')
            ->type('@tallstackui_autocomplete_input', 'Sao')
            ->pause(300)
            ->assertSee('São Paulo')
            ->assertDontSee('Santos');
    }

    #[Test]
    public function can_filter_ignoring_accents_in_the_search(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="[
                        ['value' => 'Sao Paulo', 'description' => 'Brasil'],
                        ['value' => 'Santos', 'description' => 'Brasil'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Santos')
            ->type('@tallstackui_autocomplete_input', 'São')
            ->pause(300)
            ->assertSee('Sao Paulo')
            ->assertDontSee('Santos');
    }

    #[Test]
    public function can_keep_the_highlighted_item_in_view_while_navigating_with_arrow_keys(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :items="collect(range(1, 40))->map(fn (int $index) => ['value' => 'Item '.$index])->all()" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Item 1')
            ->keys('@tallstackui_autocomplete_input', ...array_fill(0, 20, '{ARROW_DOWN}'))
            ->pause(300)
            ->tap(function (Browser $browser): void {
                $state = $browser->script(<<<'JS'
                    const list = document.querySelector('[dusk="tallstackui_autocomplete_options"]');
                    const row = list.querySelector('[data-index="19"]');
                    const outer = list.getBoundingClientRect();
                    const inner = row.getBoundingClientRect();

                    return JSON.stringify({
                        scrollTop: list.scrollTop,
                        visible: inner.top >= outer.top && inner.bottom <= outer.bottom,
                    });
                JS)[0];

                $payload = json_decode($state, true);

                $this->assertGreaterThan(0, $payload['scrollTop'], 'The list must scroll to follow the highlighted item.');
                $this->assertTrue($payload['visible'], 'The highlighted item must stay inside the visible area of the list.');
            });
    }

    #[Test]
    public function can_keep_the_server_value_on_a_remote_source(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $picked = 'et porro tempora';

            public int $synced = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked ?? 'null' }}</p>
                    <p dusk="synced">{{ $synced }}</p>
                    <x-autocomplete wire:model="picked" request="/searchable-filtered" />
                    <button type="button" dusk="sync" wire:click="sync">Sync</button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->synced++;
            }
        })
            ->assertInputValue('@tallstackui_autocomplete_input', 'et porro tempora')
            ->click('@sync')
            ->waitForTextIn('@synced', '1')
            ->assertSeeIn('@picked', 'et porro tempora');
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
    public function can_send_the_request_params(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :request="[
                        'url' => '/searchable-echoing-parameters',
                        'params' => ['raw' => 'foo'],
                    ]" select="value:label" />
                </div>
                HTML;
            }
        })
            ->type('@tallstackui_autocomplete_input', 'raw')
            ->waitForText('raw:foo')
            ->assertSee('raw:foo');
    }

    #[Test]
    public function can_send_the_request_params_through_post(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete :request="[
                        'url' => '/autocomplete-echoing-parameters',
                        'method' => 'post',
                        'params' => ['raw' => 'foo'],
                    ]" />
                </div>
                HTML;
            }
        })
            ->type('@tallstackui_autocomplete_input', 'raw')
            ->waitForText('raw:foo search:raw')
            ->assertSee('raw:foo search:raw');
    }

    #[Test]
    public function can_show_the_validation_error(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $city = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-autocomplete wire:model="city" :items="[
                        ['value' => 'Foo'],
                    ]" />
                    <button type="button" dusk="save" wire:click="save">Save</button>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->validate(['city' => 'required']);
            }
        })
            ->click('@save')
            ->waitForText('The city field is required.')
            ->assertSee('The city field is required.');
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
    public function strict_drops_a_value_missing_from_the_local_items(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $picked = 'Baz';

            public int $synced = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked ?? 'null' }}</p>
                    <p dusk="synced">{{ $synced }}</p>
                    <x-autocomplete wire:model="picked" strict :items="[
                        ['value' => 'Foo'],
                        ['value' => 'Bar'],
                    ]" />
                    <button type="button" dusk="sync" wire:click="sync">Sync</button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->synced++;
            }
        })
            ->assertInputValue('@tallstackui_autocomplete_input', '')
            ->click('@sync')
            ->waitForTextIn('@synced', '1')
            ->assertSeeIn('@picked', 'null');
    }

    #[Test]
    public function strict_keeps_the_server_value_on_a_remote_source(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $picked = 'et porro tempora';

            public int $synced = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked ?? 'null' }}</p>
                    <p dusk="synced">{{ $synced }}</p>
                    <x-autocomplete wire:model="picked" strict request="/searchable-filtered" />
                    <button type="button" dusk="sync" wire:click="sync">Sync</button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->synced++;
            }
        })
            ->assertInputValue('@tallstackui_autocomplete_input', 'et porro tempora')
            ->click('@sync')
            ->waitForTextIn('@synced', '1')
            ->assertSeeIn('@picked', 'et porro tempora')
            ->assertInputValue('@tallstackui_autocomplete_input', 'et porro tempora');
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

    #[Test]
    public function strict_shows_a_value_set_by_the_server_on_a_remote_source(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $picked = null;

            public function load(): void
            {
                $this->picked = 'et porro tempora';
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="picked">{{ $picked ?? 'null' }}</p>
                    <x-autocomplete wire:model="picked" strict request="/searchable-filtered" />
                    <button type="button" dusk="load" wire:click="load">Load</button>
                </div>
                HTML;
            }
        })
            ->assertInputValue('@tallstackui_autocomplete_input', '')
            ->click('@load')
            ->waitForTextIn('@picked', 'et porro tempora')
            ->pause(150)
            ->assertInputValue('@tallstackui_autocomplete_input', 'et porro tempora');
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->post('/autocomplete-echoing-parameters', fn (Request $request): array => [
            ['value' => 'raw:'.$request->input('raw', 'none').' search:'.$request->input('search', 'none')],
        ]);
    }
}
