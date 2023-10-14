<?php

namespace Tests\Browser\Tab;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Tab\Components\TabComponent;
use Tests\Browser\Tab\Components\TabEntangleComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_select(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, TabComponent::class)
                ->assertSee('Foo')
                ->assertSee('Bar')
                ->assertSee('Foo bar baz')
                ->assertDontSee('Baz bar foo')
                ->clickAtXPath('/html/body/div[3]/div/ul/li[2]')
                ->waitForText('Baz bar foo')
                ->assertDontSee('Foo bar baz');
        });
    }

    /** @test */
    public function can_select_and_deselect(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, TabComponent::class)
                ->assertSee('Foo')
                ->assertSee('Bar')
                ->assertSee('Foo bar baz')
                ->assertDontSee('Baz bar foo')
                ->clickAtXPath('/html/body/div[3]/div/ul/li[2]')
                ->waitForText('Baz bar foo')
                ->assertDontSee('Foo bar baz')
                ->clickAtXPath('/html/body/div[3]/div/ul/li[1]')
                ->waitForText('Foo bar baz')
                ->assertDontSee('Baz bar foo');
        });
    }

    /** @test */
    public function can_select_with_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, TabEntangleComponent::class)
                ->assertSee('Foo')
                ->assertSee('Bar')
                ->assertSee('Baz bar foo')
                ->assertDontSee('Foo bar baz')
                ->click('#change')
                ->waitForText('Foo bar baz')
                ->assertDontSee('Baz bar foo');
        });
    }
}
