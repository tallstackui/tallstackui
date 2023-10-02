<?php

namespace Tests\Browser\Select;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Select\Components\SearchableComponent;
use Tests\Browser\Select\Components\SearchableFilteredComponent;

class SearchableTest extends BrowserTestCase
{
    /** @test */
    public function can_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->click('#tasteui_open_close')
                ->pause(250)
                ->assertSee('delectus aut autem');
        });
    }

    /** @test */
    public function can_search_and_filter(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableFilteredComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->assertDontSee('et porro tempora')
                ->assertDontSee('quis ut nam facilis et officia qui')
                ->click('#tasteui_open_close')
                ->pause(250)
                ->assertSee('delectus aut autem')
                ->assertSee('et porro tempora')
                ->assertSee('quis ut nam facilis et officia qui')
                ->type('#tasteui_search_input', 'porro')
                ->pause(500)
                ->assertDontSee('delectus aut autem')
                ->assertSee('et porro tempora')
                ->assertDontSee('quis ut nam facilis et officia qui');
        });
    }

    /** @test */
    public function can_search_and_clear(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, SearchableFilteredComponent::class)
                ->assertSee('Select an option')
                ->assertDontSee('delectus aut autem')
                ->click('#tasteui_open_close')
                ->pause(250)
                ->assertSee('delectus aut autem')
                ->clickAtXPath('/html/body/div[3]/div/div[2]/div[2]/ul/li[1]')
                ->pause(250)
                ->assertSee('delectus aut autem')
                ->click('#tasteui_clear')
                ->assertDontSee('delectus aut autem')
                ->assertSee('Select an option');
        });
    }
}
