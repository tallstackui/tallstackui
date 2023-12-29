<?php

namespace Tests\Browser\Select;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class StyledCommonTest extends BrowserTestCase
{
    /** @test */
    public function can_clear(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@sync')
            ->waitForTextIn('@string', 'foo')
            ->click('@tallstackui_select_clear')
            ->click('@sync')
            ->waitUntilMissingText('foo')
            ->assertSee('Select an option');
    }

    /** @test */
    public function can_interact_with_multiples_selects(): void
    {
        Livewire::visit(StyledComponentMultipleSelect_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('Type 1')
            ->assertDontSee('Type 2')
            ->assertDontSee('Type 3')
            ->click('@tallstackui_select_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->waitForText(['Type 1', 'Type 2', 'Type 3'])
            // Type 1
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[2]')
            ->waitForTextIn('@type', '1')
            ->waitForText(['AAA', 'BBB'])
            // Type 2
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['AAA', 'BBB', 'CCC'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->waitForText(['CCC', 'DDD'])
            ->assertSee('CCC')
            ->assertSee('DDD')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[2]')
            ->waitForTextIn('@type', '2')
            ->waitForText(['CCC', 'DDD'])
            // Type 3
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['AAA', 'BBB', 'CCC'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->waitForText(['EEE', 'FFF'])
            ->assertSee('EEE')
            ->assertSee('FFF')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[2]')
            ->waitForTextIn('@type', '3')
            ->waitForText(['EEE', 'FFF'])
            // Backing to Type 1
            ->click('@tallstackui_select_open_close')
            ->waitForText(['Type 1', 'Type 2', 'Type 3'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->waitForTextIn('@type', '1')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/button')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div[2]/div[2]/div/ul/li[2]')
            ->waitForText(['AAA', 'BBB'])
            ->assertSee('AAA')
            ->assertSee('BBB')
            ->assertDontSee('CCC')
            ->assertDontSee('DDD')
            ->assertDontSee('EEE')
            ->assertDontSee('FFF');
    }

    /** @test */
    public function can_open(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('bar')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar']);
    }

    /** @test */
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

    /** @test */
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

    /** @test */
    public function can_select(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('foo')
            ->waitUntilMissingText('bar')
            ->assertDontSee('bar')
            ->assertDontSee('Select an option');
    }

    /** @test */
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
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->waitForText('foo')
            ->waitUntilMissingText('bar')
            ->assertDontSee('bar')
            ->assertDontSee('Select an option');
    }

    /** @test */
    public function can_select_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->assertDontSee('Select an option');
    }

    /** @test */
    public function can_select_multiple_with_live_entangle(): void
    {
        Livewire::visit(StyledMultipleLiveEntangleComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->assertDontSee('Select an option');
    }

    /** @test */
    public function can_select_multiple_with_live_entangle_preserving_default(): void
    {
        Livewire::visit(StyledMultipleLiveEntangleDefaultComponent_Common::class)
            ->assertDontSee('Select an option')
            ->assertSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->waitForText(['bar', 'baz', 'foo'])
            ->assertDontSee('Select an option');
    }

    /** @test */
    public function can_select_multiple_with_same_label(): void
    {
        Livewire::visit(StyledMultipleComponentSameLabel_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->waitForText(['baz', 'bar', 'bah'])
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['baz', 'bah'])
            ->click('@tallstackui_select_open_close')
            ->waitForText('foo')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText('baz');
    }

    /** @test */
    public function can_unselect(): void
    {
        Livewire::visit(StyledComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('foo')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('Select an option')
            ->waitUntilMissingText('foo')
            ->assertDontSee('foo');
    }

    /** @test */
    public function can_unselect_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Common::class)
            ->assertSee('Select an option')
            ->assertDontSee('foo')
            ->assertDontSee('bar')
            ->assertDontSee('baz')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->click('@sync')
            ->waitForText(['foo', 'bar', 'baz'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['foo', 'bar', 'baz'])
            ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['foo', 'bar']);
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

class StyledMultipleComponentSameLabel_Common extends Component
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
}

class StyledMultipleLiveEntangleComponent_Common extends Component
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
}

class StyledMultipleLiveEntangleDefaultComponent_Common extends Component
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
}

class StyledComponentMultipleSelect_Common extends Component
{
    public ?Collection $devices = null;

    public ?array $selected = null;

    public ?int $type = null;

    public ?Collection $types = null;

    public function mount(): void
    {
        $this->types = collect([
            ['label' => 'Type 1', 'value' => 1],
            ['label' => 'Type 2', 'value' => 2],
            ['label' => 'Type 3', 'value' => 3],
        ]);

        $this->devices = collect();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="type">{{ $type }}</p>
            <p dusk="selected">@json($selected)</p>

            <x-select.styled :options="$types?->toArray()"
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

    public function updated($property, $value): void
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
}
