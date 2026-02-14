<?php

namespace TallStackUi\Components\Form\Select\Styled;

use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class SelectStyledCommonBrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_change_selectable(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ $string }}

                    <x-select.styled wire:model="string"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['name' => 'foo', 'id' => 'foo', 'text' => 'PHP', 'some_picture' => 'https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png'],
                                        ['name' => 'bar', 'id' => 'bar', 'text' => 'JS', 'some_picture' => null],
                                     ]"
                                     select="label:name|value:id|description:text|image:some_picture">
                    </x-select.styled>

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->assertDontSee('PHP')
            ->assertDontSee('JS')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'PHP', 'JS'])
            ->assertVisible('@tallstackui_select_options')
            ->assertSee('foo')
            ->assertSee('bar')
            ->assertSee('PHP')
            ->assertSee('JS')
            ->assertVisible('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@sync')
            ->waitForText('foo')
            ->assertVisible('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]')
            ->assertDontSee('bar');
    }

    #[Test]
    public function can_clear(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@sync')
            ->waitForTextIn('@string', 'foo')
            ->click('@tallstackui_select_clear')
            ->click('@sync')
            ->waitUntilMissingText('foo')
            ->assertSee('Select an option');
    }

    #[Test]
    public function can_close_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-button dusk="close" x-on:click="$tsui.close.select('test')">Close</x-button>

                    <x-select.styled wire:model="string"
                                     id="test"
                                     label="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]"
                                     select="label:label|value:value"
                    />
                </div>
                HTML;
            }
        })
            ->assertSee('Select an option')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->assertVisible('@tallstackui_select_options')
            ->click('@close')
            ->waitUntilMissingText('foo')
            ->assertDontSee('foo')
            ->assertDontSee('bar');
    }

    #[Test]
    public function can_dispatch_events(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $options = [];

            public bool $select = false;

            public bool $remove = false;

            public bool $erase = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="options">@json($options)</p>

                    @if ($select)
                        <p dusk="select">Select</p>
                    @endif

                    @if ($remove)
                        <p dusk="remove">Remove</p>
                    @endif

                    @if ($erase)
                        <p dusk="erase">Erase</p>
                    @endif

                    <x-select.styled wire:model.live="options"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                        ['label' => 'baz', 'value' => 'baz'],
                                     ]"
                                     multiple
                                     x-on:select="$wire.set('select', 1)"
                                     x-on:remove="$wire.set('remove', 1)"
                                     select="label:label|value:value"
                    />
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->waitForLivewire()
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForText('Select')
            ->assertVisible('@select')
            ->clickAtXPath('//button[@dusk="tallstackui_select_open_close"]/div[1]/div/div[3]/a/div/div[2]/button')
            ->waitForText('Remove')
            ->assertVisible('@remove');
    }

    #[Test]
    public function can_hydrate_grouped_options_with_default_values(): void
    {
        Livewire::visit(new class extends Component
        {
            public array $cities = [4, 5];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="cities">@json($cities)</p>

                    <x-select.styled wire:model="cities"
                                     label="Cities"
                                     :options="[
                                        [
                                            'label' => 'Brazil',
                                            'value' => [
                                                ['label' => 'São Paulo', 'value' => 4],
                                                ['label' => 'Rio de Janeiro', 'value' => 5],
                                                ['label' => 'Brasília', 'value' => 6],
                                            ]
                                        ],
                                        [
                                            'label' => 'United States',
                                            'value' => [
                                                ['label' => 'New York', 'value' => 7],
                                                ['label' => 'Los Angeles', 'value' => 8],
                                                ['label' => 'Chicago', 'value' => 9],
                                            ]
                                        ],
                                     ]"
                                     select="label:label|value:value"
                                     multiple
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->waitForLivewireToLoad()
            ->pause(500)
            ->assertDontSee('Select an option')
            ->assertSeeIn('@tallstackui_select_open_close', 'São Paulo')
            ->assertSeeIn('@tallstackui_select_open_close', 'Rio de Janeiro');
    }

    #[Test]
    public function can_hydrate_grouped_single_option_with_default_value(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $city = 7;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="city">{{ $city }}</p>

                    <x-select.styled wire:model="city"
                                     label="City"
                                     :options="[
                                        [
                                            'label' => 'Brazil',
                                            'value' => [
                                                ['label' => 'São Paulo', 'value' => 4],
                                                ['label' => 'Rio de Janeiro', 'value' => 5],
                                            ]
                                        ],
                                        [
                                            'label' => 'United States',
                                            'value' => [
                                                ['label' => 'New York', 'value' => 7],
                                                ['label' => 'Los Angeles', 'value' => 8],
                                            ]
                                        ],
                                     ]"
                                     select="label:label|value:value"
                    />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->pause(500)
            ->assertDontSee('Select an option')
            ->assertSeeIn('@tallstackui_select_open_close', 'New York');
    }

    #[Test]
    public function can_interact_with_multiples_selects(): void
    {
        $this->skipOnGitHubActions();

        Livewire::visit(new class extends Component
        {
            public ?Collection $devices = null;

            public ?array $selected = null;

            public ?int $type = null;

            public array $types = [
                ['label' => 'Type 1', 'value' => 1],
                ['label' => 'Type 2', 'value' => 2],
                ['label' => 'Type 3', 'value' => 3],
            ];

            public function mount(): void
            {
                $this->devices = collect();
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="type">{{ $type }}</p>
                    <p dusk="selected">@json($selected)</p>

                    <x-select.styled label="Select One Option" :options="$types"
                                    wire:model.live="type"
                                    select="label:label|value:value" />

                    @if ($devices->isNotEmpty())
                    <x-select.styled :options="$devices->toArray()"
                                    wire:model.live="selected"
                                    select="label:label|value:value" multiple />
                    @endif
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }

            public function updated(string $property, mixed $value): void
            {
                if ($property === 'type') {
                    (match ($value) { // @phpstan-ignore-line
                        1 => function () {
                            $this->selected = null;
                            $this->devices = collect([
                                ['label' => 'AAA', 'value' => 'AAA'],
                                ['label' => 'BBB', 'value' => 'BBB'],
                            ]);
                        },
                        2 => function () {
                            $this->selected = null;
                            $this->devices = collect([
                                ['label' => 'CCC', 'value' => 'CCC'],
                                ['label' => 'DDD', 'value' => 'DDD'],
                            ]);
                        },
                        3 => function () {
                            $this->selected = null;
                            $this->devices = collect([
                                ['label' => 'EEE', 'value' => 'EEE'],
                                ['label' => 'FFF', 'value' => 'FFF'],
                            ]);
                        },
                        null => function () {
                            $this->selected = null;
                            $this->devices = collect();
                        }
                    })();
                }
            }
        })
            ->assertDontSee('Type 1')
            ->assertDontSee('Type 2')
            ->assertDontSee('Type 3')
            ->waitForText('Select One Option')
            ->assertSee('Select One Option')
            ->click('@tallstackui_select_open_close')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForText(['Type 1', 'Type 2', 'Type 3'])
            // Type 1
            ->waitForText('Select an option')
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[1]')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[2]')
            ->waitForTextIn('@type', '1')
            ->waitForText(['AAA', 'BBB'])
            // Type 2
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['AAA', 'BBB', 'CCC'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->waitForText(['CCC', 'DDD'])
            ->assertSee('CCC')
            ->assertSee('DDD')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[1]')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[2]')
            ->waitForTextIn('@type', '2')
            ->waitForText(['CCC', 'DDD'])
            // Type 3
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['AAA', 'BBB', 'CCC'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->waitForText(['EEE', 'FFF'])
            ->assertSee('EEE')
            ->assertSee('FFF')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[1]')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[2]')
            ->waitForTextIn('@type', '3')
            ->waitForText(['EEE', 'FFF'])
            // Backing to Type 1
            ->click('@tallstackui_select_open_close')
            ->waitForText(['Type 1', 'Type 2', 'Type 3'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@type', '1')
            ->clickAtXPath('(//button[@dusk="tallstackui_select_open_close"])[2]')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[1]')
            ->clickAtXPath('(//ul[@dusk="tallstackui_select_options"])[2]/li[2]')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->assertDontSee('CCC')
            ->assertDontSee('DDD')
            ->assertDontSee('EEE')
            ->assertDontSee('FFF');
    }

    #[Test]
    public function can_keep_full_options(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $something = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($something)
                        <p dusk="something">{{ $something }}</p>
                    @endif

                    <x-select.styled
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo', 'something' => 'foo-bar'],
                                        ['label' => 'bar', 'value' => 'bar', 'something' => 'foo-baz'],
                                        ['label' => 'baz', 'value' => 'baz', 'something' => 'baz-bar'],
                                     ]"
                                     x-on:select="$wire.set('something', $event.detail.select.something)"
                                     select="label:label|value:value"
                    />
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->assertDontSee('foo-bar')
            ->assertDontSee('foo-baz')
            ->assertDontSee('baz-bar')
            ->waitForLivewire()
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->assertVisible('@something')
            ->waitForText('foo-bar');
    }

    #[Test]
    public function can_navigate_grouped_options_with_keyboard(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $city = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="city">{{ $city }}</p>

                    <x-select.styled wire:model="city"
                                     label="City"
                                     :options="[
                                        [
                                            'label' => 'Brazil',
                                            'value' => [
                                                ['label' => 'São Paulo', 'value' => 4],
                                                ['label' => 'Rio de Janeiro', 'value' => 5],
                                            ]
                                        ],
                                        [
                                            'label' => 'United States',
                                            'value' => [
                                                ['label' => 'New York', 'value' => 7],
                                                ['label' => 'Los Angeles', 'value' => 8],
                                            ]
                                        ],
                                     ]"
                                     select="label:label|value:value"
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['Brazil', 'São Paulo', 'New York'])
            ->keys('@tallstackui_select_open_close', '{arrow_down}')
            ->pause(200)
            ->keys('@tallstackui_select_open_close', '{arrow_down}')
            ->pause(200)
            ->keys('@tallstackui_select_open_close', '{arrow_down}')
            ->pause(200)
            ->tap(fn (Browser $browser) => $browser->script('document.activeElement.click()'))
            ->pause(300)
            ->click('@sync')
            ->waitForTextIn('@city', '7')
            ->assertSeeIn('@tallstackui_select_open_close', 'New York');
    }

    #[Test]
    public function can_open(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar']);
    }

    #[Test]
    public function can_open_using_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-select.styled wire:model="string"
                                     id="test"
                                     label="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]"
                                     select="label:label|value:value"
                    />

                    <x-button dusk="open" x-on:click="$tsui.open.select('test')">Open</x-button>
                </div>
                HTML;
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@open')
            ->waitForText(['foo', 'bar'])
            ->assertVisible('@tallstackui_select_options');
    }

    #[Test]
    public function can_render_after_slot(): void
    {
        Livewire::visit(StyledSearchableComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->waitUntilMissingText('Ooops')
            ->assertDontSee('Ooops')
            ->type('@tallstackui_select_search_input', 'foo,bar,baz')
            ->waitForText('Ooops');
    }

    #[Test]
    public function can_search(): void
    {
        Livewire::visit(StyledSearchableComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->type('@tallstackui_select_search_input', 'bar')
            ->waitForText('bar')
            ->assertSee('bar')
            ->waitUntilMissingText('foo')
            ->assertDontSee('foo');
    }

    #[Test]
    public function can_search_using_description(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ $string }}

                    <x-select.styled wire:model="string"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo', 'description' => 'PHP'],
                                        ['label' => 'bar', 'value' => 'bar', 'description' => 'JS'],
                                     ]"
                                     select="label:label|value:value" searchable>
                        <x-slot:after>
                            Ooops!
                        </x-slot:after>
                    </x-select.styled>

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->type('@tallstackui_select_search_input', 'PHP')
            ->waitForText('foo')
            ->assertSee('foo')
            ->waitForText('PHP')
            ->assertSee('PHP')
            ->waitUntilMissingText('bar')
            ->assertDontSee('bar')
            ->assertDontSee('JS');
    }

    #[Test]
    public function can_search_within_grouped_options(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $city = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="city">{{ $city }}</p>

                    <x-select.styled wire:model="city"
                                     label="City"
                                     :options="[
                                        [
                                            'label' => 'Brazil',
                                            'value' => [
                                                ['label' => 'São Paulo', 'value' => 4],
                                                ['label' => 'Rio de Janeiro', 'value' => 5],
                                            ]
                                        ],
                                        [
                                            'label' => 'United States',
                                            'value' => [
                                                ['label' => 'New York', 'value' => 7],
                                                ['label' => 'Los Angeles', 'value' => 8],
                                            ]
                                        ],
                                     ]"
                                     select="label:label|value:value"
                                     searchable
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['Brazil', 'United States', 'São Paulo', 'New York'])
            ->type('@tallstackui_select_search_input', 'York')
            ->waitForText('New York')
            ->assertSee('United States')
            ->assertSee('New York')
            ->waitUntilMissingText('São Paulo')
            ->assertDontSee('São Paulo')
            ->assertDontSee('Brazil');
    }

    #[Test]
    public function can_see_validation_errors(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-select.styled wire:model.live="string"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]" />
                </div>
                HTML;
            }
        })
            ->assertDontSee('@tallstackui_select_clear')
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@string', 'foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->waitForLivewire()->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@tallstackui_select_open_close')
            ->assertSee('The string field is required.');
    }

    #[Test]
    public function can_select(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@sync')
            ->waitForText('foo')
            ->waitUntilMissingText('bar')
            ->assertDontSee('bar')
            ->assertDontSee('Select an option');
    }

    #[Test]
    public function can_select_after_opening_with_helper(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-select.styled wire:model="string"
                                     id="test"
                                     label="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]"
                                     select="label:label|value:value"
                    />

                    <x-button dusk="open" x-on:click="$tsui.open.select('test')">Open</x-button>
                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->click('@open')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@sync')
            ->waitForTextIn('@string', 'foo');
    }

    #[Test]
    public function can_select_and_dispatch_change_event(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-select.styled wire:model="string"
                                     wire:change="sync"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]"
                                     select="label:label|value:value"
                    />
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForText('foo')
            ->waitUntilMissingText('bar')
            ->assertDontSee('bar')
            ->assertDontSee('Select an option');
    }

    #[Test]
    public function can_select_grouped_options(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?int $city = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="city">{{ $city }}</p>

                    <x-select.styled wire:model="city"
                                     label="City"
                                     :options="[
                                        [
                                            'label' => 'Brazil',
                                            'value' => [
                                                ['label' => 'São Paulo', 'value' => 4],
                                                ['label' => 'Rio de Janeiro', 'value' => 5],
                                            ]
                                        ],
                                        [
                                            'label' => 'United States',
                                            'value' => [
                                                ['label' => 'New York', 'value' => 7],
                                                ['label' => 'Los Angeles', 'value' => 8],
                                            ]
                                        ],
                                     ]"
                                     select="label:label|value:value"
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['Brazil', 'United States', 'São Paulo', 'New York'])
            ->assertSee('Brazil')
            ->assertSee('United States')
            ->assertSee('São Paulo')
            ->assertSee('New York')
            ->clickAtXPath('//li[contains(., "São Paulo")]')
            ->click('@sync')
            ->waitForTextIn('@city', '4')
            ->assertSeeIn('@tallstackui_select_open_close', 'São Paulo');
    }

    #[Test]
    public function can_select_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->assertDontSee('Select an option');
    }

    #[Test]
    public function can_select_multiple_with_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $array = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ implode(',', $array ?? []) }}

                    <x-select.styled wire:model.live="array"
                                    label="Select"
                                    hint="Select"
                                    :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                        ['label' => 'baz', 'value' => 'baz'],
                                    ]"
                                    select="label:label|value:value"
                                    multiple
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->assertDontSee('Select an option');
    }

    #[Test]
    public function can_select_multiple_with_live_entangle_preserving_default(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $array = ['foo'];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ implode(',', $array ?? []) }}

                    <x-select.styled wire:model.live="array"
                                    label="Select"
                                    hint="Select"
                                    :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                        ['label' => 'baz', 'value' => 'baz'],
                                    ]"
                                    select="label:label|value:value"
                                    searchable
                                    multiple
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertDontSee('Select an option')
            ->assertSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForText(['bar', 'baz', 'foo'])
            ->assertDontSee('Select an option');
    }

    #[Test]
    public function can_select_multiple_with_same_label(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $array = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ implode(',', $array ?? []) }}

                    <x-select.styled wire:model.live="array"
                                    label="Select"
                                    hint="Select"
                                    :options="[
                                        ['label' => 'foo', 'value' => 'bar'],
                                        ['label' => 'foo', 'value' => 'baz'],
                                        ['label' => 'foo', 'value' => 'bah'],
                                    ]"
                                    select="label:label|value:value"
                                    multiple
                    />
                </div>
                HTML;
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->waitForLivewire()->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForLivewire()->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->waitForLivewire()->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->waitForText(['baz', 'bar', 'bah'])
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['baz', 'bah'])
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText('baz');
    }

    #[Test]
    public function can_select_with_limit_option(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?array $array = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    {{ implode(',', $array ?? []) }}

                    <x-select.styled wire:model="array"
                                    label="Select"
                                    hint="Select"
                                    :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                        ['label' => 'baz', 'value' => 'baz'],
                                        ['label' => 'bah', 'value' => 'bah'],
                                    ]"
                                    select="label:label|value:value"
                                    limit="2"
                                    multiple
                    />

                    <x-button dusk="sync" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                // ...
            }
        })
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz', 'bah'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[4]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['foo', 'baz'])
            ->waitUntilMissingText('bah')
            ->assertDontSee('bah');
    }

    #[Test]
    public function can_unselect(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@sync')
            ->waitForText('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText('Select an option')
            ->waitUntilMissingText('foo')
            ->assertDontSee('foo');
    }

    #[Test]
    public function can_unselect_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[2]')
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[3]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['foo', 'bar']);
    }

    #[Test]
    public function cannot_unselect_when_selection_is_required(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $string = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="string">{{ $string }}</p>

                    <x-select.styled wire:model.live="string"
                                     label="Select"
                                     hint="Select"
                                     :options="[
                                        ['label' => 'foo', 'value' => 'foo'],
                                        ['label' => 'bar', 'value' => 'bar'],
                                     ]" select="label:label|value:value" required />
                </div>
                HTML;
            }
        })
            ->assertDontSee('@tallstackui_select_clear')
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@string', 'foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('//ul[@dusk="tallstackui_select_options"]/li[1]')
            ->waitForTextIn('@string', 'foo')
            ->assertDontSee('Select an option');
    }
}

class StyledComponent_Common extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="string">{{ $string }}</p>

            <x-select.styled wire:model="string"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'foo'],
                                ['label' => 'bar', 'value' => 'bar'],
                             ]"
                             select="label:label|value:value"
            />

            <x-button dusk="sync" wire:click="sync">Sync</x-button>
        </div>
        HTML;
    }

    public function sync(): void
    {
        // ...
    }
}

class StyledSearchableComponent_Common extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'foo'],
                                ['label' => 'bar', 'value' => 'bar'],
                             ]"
                             select="label:label|value:value" searchable>
                <x-slot:after>
                    Ooops!
                </x-slot:after>
            </x-select.styled>

            <x-button dusk="sync" wire:click="sync">Sync</x-button>
        </div>
        HTML;
    }

    public function sync(): void
    {
        // ...
    }
}

class StyledMultipleComponent_Common extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ implode(',', $array ?? []) }}

            <x-select.styled wire:model="array"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'foo'],
                                ['label' => 'bar', 'value' => 'bar'],
                                ['label' => 'baz', 'value' => 'baz'],
                             ]"
                             select="label:label|value:value"
                             multiple
            />

            <x-button dusk="sync" wire:click="sync">Sync</x-button>
        </div>
        HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
