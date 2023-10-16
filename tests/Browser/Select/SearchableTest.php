<?php

namespace Tests\Browser\Select;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Select\Components\Searchable\SearchableAfterComponent;
use Tests\Browser\Select\Components\Searchable\SearchableComponent;
use Tests\Browser\Select\Components\Searchable\SearchableFilteredComponent;
use Tests\Browser\Select\Components\Searchable\SearchableLoadLiveEntangleComponent;
use Tests\Browser\Select\Components\Searchable\SearchableMultipleComponent;
use Tests\Browser\Select\Components\Searchable\SearchableMultipleEntangleLiveComponent;
use Tests\Browser\Select\Components\Searchable\SearchableMultipleEntangleLiveDefaultComponent;

class SearchableTest extends BrowserTestCase
{
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
    public function can_deselect_single_in_multiple_with_live_entangle_preserving_others(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableMultipleEntangleLiveDefaultComponent::class)
                ->assertDontSee('Select an option')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->waitForText('quis ut nam facilis et officia qui')
                ->waitForText('fugiat veniam minus')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[3]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('[1,3]');
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
    public function can_load_default_based_on_entangle_live(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableLoadLiveEntangleComponent::class)
                ->assertSee('Select an option')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->waitForText('quis ut nam facilis et officia qui')
                ->waitForText('fugiat veniam minus')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div[1]/select/option[2]')
                ->waitForText('quis ut nam facilis et officia qui')
                ->waitForText('2');
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
                ->waitForText('After Slot');
        });
    }

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
    public function can_select_multiple_with_live_entangle_preserving_default(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableMultipleEntangleLiveComponent::class)
                ->assertSee('Select an option')
                ->click('@tallstackui_select_open_close')
                ->waitForText('delectus aut autem')
                ->waitForText('quis ut nam facilis et officia qui')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('[1,2]');
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
}
