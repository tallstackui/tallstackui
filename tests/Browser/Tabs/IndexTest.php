<?php

namespace Tests\Browser\Tabs;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Tabs\Components\TabsComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_select(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, TabsComponent::class)
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
            $this->visit($browser, TabsComponent::class)
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
}
