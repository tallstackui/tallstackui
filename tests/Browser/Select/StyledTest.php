<?php

namespace Tests\Browser\Select;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Select\Components\Styled\StyledAfterComponent;
use Tests\Browser\Select\Components\Styled\StyledComponent;
use Tests\Browser\Select\Components\Styled\StyledMultipleComponent;
use Tests\Browser\Select\Components\Styled\StyledMultipleEntangleLiveComponent;
use Tests\Browser\Select\Components\Styled\StyledMultipleEntangleLiveWithDefaultComponent;
use Tests\Browser\Select\Components\Styled\StyledSearchableComponent;

class StyledTest extends BrowserTestCase
{
    /** @test */
    public function can_clear(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('#sync')
                ->waitForText('bar')
                ->click('@tallstackui_select_clear')
                ->click('#sync')
                ->waitUntilMissingText('bar');
        });
    }

    /** @test */
    public function can_deselect_single_in_multiple_with_live_entangle_preserving_others()
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleEntangleLiveWithDefaultComponent::class)
                ->assertSee('foo')
                ->assertSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('["foo","baz"]');
        });
    }

    /** @test */
    public function can_render_after_slot(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledAfterComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('#sync')
                ->waitForText('bar')
                ->click('@tallstackui_select_open_close')
                ->type('@tallstackui_select_search_input', 'porro')
                ->waitForText('After Slot')
                ->click('@tallstackui_select_clear')
                ->click('#sync')
                ->waitUntilMissingText('bar');
        });
    }

    /** @test */
    public function can_search()
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledSearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->type('@tallstackui_select_search_input', 'bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('#sync')
                ->waitForText('foo');
        });
    }

    /** @test */
    public function can_select(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('#sync')
                ->waitForText('bar');
        });
    }

    /** @test */
    public function can_select_multiple()
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->click('#sync')
                ->waitForText('["bar","foo"]');
        });
    }

    /** @test */
    public function can_select_multiple_with_live_entangle()
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleEntangleLiveComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('["bar","foo"]');
        });
    }

    /** @test */
    public function can_select_multiple_with_live_entangle_preserving_default()
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleEntangleLiveWithDefaultComponent::class)
                ->assertSee('foo')
                ->assertSee('bar')
                ->assertDontSee('["bar","foo","baz"]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('["bar","foo","baz"]');
        });
    }
}
