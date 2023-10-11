<?php

namespace Tests\Browser\Select;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Select\Components\Searchable\SearchableAfterComponent;
use Tests\Browser\Select\Components\Searchable\SearchableComponent;
use Tests\Browser\Select\Components\Searchable\SearchableFilteredComponent;
use Tests\Browser\Select\Components\Searchable\SearchableMultipleComponent;

class SearchableTest extends BrowserTestCase
{
    /** @test */
    public function can_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem');
        });
    }

    /** @test */
    public function can_filter(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableFilteredComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->assertDontSee('et porro tempora')
                ->assertDontSee('quis ut nam facilis et officia qui')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->assertSee('delectus aut autem')
                ->assertSee('et porro tempora')
                ->assertSee('quis ut nam facilis et officia qui')
                ->type('@tallstackui_select_search_input', 'porro')
                ->waitUntilMissingText('delectus aut autem')
                ->waitForText('et porro tempora')
                ->waitUntilMissingText('quis ut nam facilis et officia qui');
        });
    }

    /** @test */
    public function can_clear(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableFilteredComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[1]')
                ->waitForText('delectus aut autem')
                ->click('@tallstackui_select_clear')
                ->assertDontSee('delectus aut autem')
                ->assertSee('Select an option');
        });
    }

    /** @test */
    public function can_select_single(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('laboriosam mollitia et enim quasi adipisci quia provident illum')
                ->click('@tallstackui_select_open_close')
                ->waitForText('laboriosam mollitia et enim quasi adipisci quia provident illum')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[5]')
                ->click('#sync')
                ->waitForText('5');
        });
    }

    /** @test */
    public function can_select_multiple(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableMultipleComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->assertDontSee('quis ut nam facilis et officia qui')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->waitForText('quis ut nam facilis et officia qui')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->click('#sync')
                ->waitForText('[1,2]');
        });
    }

    /** @test */
    public function can_render_after_slot(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableAfterComponent::class)
                ->assertSee('Select an option')
                ->click('@tallstackui_select_open_close')
                ->type('@tallstackui_select_search_input', 'porro')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[1]')
                ->click('@tallstackui_select_open_close')
                ->type('@tallstackui_select_search_input', 'foo-bar-baz')
                ->waitForText('No results found')
                ->waitForText('After Slot');
        });
    }
}
