<?php

namespace Tests\Browser\Select;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class StyledSearchableTest extends BrowserTestCase
{
    /** @test */
    public function can_clear(): void
    {
        Livewire::visit(StyledComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('delectus aut autem')
            ->click('@tallstackui_select_clear')
            ->click('@sync')
            ->waitUntilMissingText('delectus aut autem')
            ->assertSee('Select an option');
    }

    /** @test */
    public function can_open(): void
    {
        Livewire::visit(StyledComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum']);
    }

    /** @test */
    public function can_render_after_slot(): void
    {
        Livewire::visit(StyledAfterSlotComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->waitUntilMissingText('Ooops')
            ->type('@tallstackui_select_search_input', 'foo,bar,baz')
            ->waitForText('Ooops');
    }

    /** @test */
    public function can_search(): void
    {
        Livewire::visit(StyledSearchableComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->type('@tallstackui_select_search_input', 'et porro tempora')
            ->waitForText('et porro tempora')
            ->waitUntilMissingText('delectus aut autem')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum');
    }

    /** @test */
    public function can_select(): void
    {
        Livewire::visit(StyledComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('delectus aut autem')
            ->assertDontSee('Select an option');
    }

    /** @test */
    public function can_select_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus']);
    }

    /** @test */
    public function can_select_multiple_with_live_entangle(): void
    {
        Livewire::visit(StyledMultipleLiveEntangleComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText('delectus aut autem')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus'])
            ->click('@sync')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus']);
    }

    /** @test */
    public function can_select_multiple_with_live_entangle_preserving_default(): void
    {
        Livewire::visit(StyledMultipleLiveEntangleDefaultComponent_Searchable::class)
            ->assertSee('delectus aut autem')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[2]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitUntilMissingText('delectus aut autem')
            ->pressAndWaitFor('@sync')
            ->waitForText(['quis ut nam facilis et officia qui', 'fugiat veniam minus'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['quis ut nam facilis et officia qui', 'fugiat veniam minus,delectus aut autem', 'delectus aut autem']);
    }

    /** @test */
    public function can_unselect(): void
    {
        Livewire::visit(StyledComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->click('@sync')
            ->waitForText('delectus aut autem')
            ->click('@tallstackui_select_clear')
            ->click('@sync')
            ->waitUntilMissingText('delectus aut autem')
            ->assertDontSee('delectus aut autem')
            ->assertSee('Select an option');
    }

    /** @test */
    public function can_unselect_multiple(): void
    {
        Livewire::visit(StyledMultipleComponent_Searchable::class)
            ->assertSee('Select an option')
            ->assertDontSee('delectus aut autem')
            ->assertDontSee('quis ut nam facilis et officia qui')
            ->assertDontSee('fugiat veniam minus')
            ->assertDontSee('et porro tempora')
            ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[1]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[2]')
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus'])
            ->click('@tallstackui_select_open_close')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui', 'fugiat veniam minus', 'et porro tempora', 'laboriosam mollitia et enim quasi adipisci quia provident illum'])
            ->clickAtXPath('/html/body/div[3]/div/div/div/ul/li[3]')
            ->click('@tallstackui_select_open_close')
            ->click('@sync')
            ->waitForText(['delectus aut autem', 'quis ut nam facilis et officia qui']);
    }
}

class StyledComponent_Searchable extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
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

class StyledMultipleComponent_Searchable extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>            
            {{ implode(',', $array ?? []) }}

            <x-select.styled wire:model="array"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
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

class StyledMultipleLiveEntangleComponent_Searchable extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ implode(',', $array ?? []) }}

            <x-select.styled wire:model.live="array"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
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

class StyledMultipleLiveEntangleDefaultComponent_Searchable extends Component
{
    public ?array $array = ['delectus aut autem'];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ implode(',', $array ?? []) }}

            <x-select.styled wire:model.live="array"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
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

class StyledSearchableComponent_Searchable extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             :request="route('searchable.filtered')"
                             label="Select"
                             hint="Select"
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

class StyledAfterSlotComponent_Searchable extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
                             select="label:label|value:value">
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
