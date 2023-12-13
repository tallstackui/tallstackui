<?php

namespace Tests\Browser\Select;

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
