<?php

namespace Tests\Browser\Select;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Select\Components\Common\StyledComponent;
use Tests\Browser\Select\Components\Common\StyledMultipleComponent;
use Tests\Browser\Select\Components\Common\StyledMultipleLiveEntangleComponent;
use Tests\Browser\Select\Components\Common\StyledMultipleLiveEntangleDefaultComponent;
use Tests\Browser\Select\Components\Common\StyledSearchableComponent;

class StyledCommonTest extends BrowserTestCase
{
    /** @test */
    public function can_clear(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@sync')
                ->waitForText('foo')
                ->click('@tallstackui_select_clear')
                ->click('@sync')
                ->waitUntilMissingText('foo')
                ->assertDontSee('foo')
                ->assertSee('Select an option');
        });
    }

    /** @test */
    public function can_open(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->assertDontSee('foo')
                ->click('@tallstackui_select_open_close')
                ->waitForText('bar')
                ->waitForText('foo')
                ->assertSee('bar')
                ->assertSee('foo');
        });
    }

    /** @test */
    public function can_render_after_slot(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledSearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->assertDontSee('foo')
                ->click('@tallstackui_select_open_close')
                ->waitForText('bar')
                ->waitForText('foo')
                ->waitUntilMissingText('Ooops')
                ->assertDontSee('Ooops')
                ->type('@tallstackui_select_search_input', 'foo,bar,baz')
                ->waitForText('Ooops')
                ->assertSee('Ooops');
        });
    }

    /** @test */
    public function can_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledSearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('bar')
                ->assertDontSee('foo')
                ->click('@tallstackui_select_open_close')
                ->waitForText('bar')
                ->waitForText('foo')
                ->type('@tallstackui_select_search_input', 'bar')
                ->waitForText('bar')
                ->assertSee('bar')
                ->waitUntilMissingText('foo')
                ->assertDontSee('foo');
        });
    }

    /** @test */
    public function can_select(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@sync')
                ->waitForText('foo')
                ->assertSee('foo')
                ->assertDontSee('Select an option');
        });
    }

    /** @test */
    public function can_select_multiple(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->assertDontSee('baz')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->click('@sync')
                ->waitForText('"foo","bar"')
                ->assertSee('"foo","bar"')
                ->assertDontSee('Select an option');
        });
    }

    /** @test */
    public function can_select_multiple_with_live_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleLiveEntangleComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->assertDontSee('baz')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('"foo"')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('"foo","bar"')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('"foo","bar","baz"')
                ->click('@tallstackui_select_open_close')
                ->click('@sync')
                ->waitForText('"foo","bar","baz"')
                ->assertSee('"foo","bar","baz"')
                ->assertDontSee('Select an option');
        });
    }

    /** @test */
    public function can_select_multiple_with_live_entangle_preserving_default(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleLiveEntangleDefaultComponent::class)
                ->assertDontSee('Select an option')
                ->assertSee('foo')
                ->assertDontSee('bar')
                ->assertDontSee('baz')
                ->click('@tallstackui_select_open_close')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->waitForText('"foo","bar"')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->waitForText('"foo","bar","baz"')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@tallstackui_select_open_close')
                ->waitForText('"bar","baz"')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->waitForText('"bar","baz","foo"')
                ->assertSee('"bar","baz","foo"')
                ->assertDontSee('Select an option');
        });
    }

    /** @test */
    public function can_unselect(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@sync')
                ->waitForText('foo')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->click('@sync')
                ->waitForText('Select an option')
                ->assertSee('Select an option')
                ->waitUntilMissingText('foo')
                ->assertDontSee('foo');
        });
    }

    /** @test */
    public function can_unselect_multiple(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, StyledMultipleComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('foo')
                ->assertDontSee('bar')
                ->assertDontSee('baz')
                ->click('@tallstackui_select_open_close')
                ->waitForText('foo')
                ->waitForText('bar')
                ->waitForText('baz')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[1]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[2]')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->click('@sync')
                ->waitForText('"foo","bar","baz"')
                ->assertSee('"foo","bar","baz"')
                ->click('@tallstackui_select_open_close')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div/ul/li[3]')
                ->click('@tallstackui_select_open_close')
                ->click('@sync')
                ->waitForText('"foo","bar"')
                ->assertSee('"foo","bar"');
        });
    }
}
