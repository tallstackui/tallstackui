<?php

namespace TallStackUi\Components\Form\Autocomplete;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

/**
 * The component outside Livewire, on a plain Blade page posting to a controller.
 * There is no Livewire component anywhere; Livewire's script is loaded only because
 * that is where Alpine comes from in a real application.
 */
class NativeBrowserTest extends BrowserTestCase
{
    #[Test]
    public function clearing_empties_the_submitted_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-autocomplete')
            ->waitFor('@tallstackui_autocomplete_input')
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Olinda')
            ->clickAtVisibleXPath('(//li[@dusk="tallstackui_autocomplete_option"][normalize-space(.)="Olinda"])[1]')
            ->pause(400)
            ->click('@tallstackui_autocomplete_clear')
            ->pause(400)
            ->assertScript("document.getElementsByName('city')[0].value", ''));
    }

    #[Test]
    public function renders_a_single_named_input(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-autocomplete')
            ->waitFor('@tallstackui_autocomplete_input')
            ->assertScript("document.getElementsByName('city').length", 1));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-autocomplete')
            ->waitFor('@tallstackui_autocomplete_input')
            ->pause(500)
            ->assertScript("document.getElementsByName('city')[0].value", ''));
    }

    #[Test]
    public function submits_the_picked_option(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-autocomplete')
            ->waitFor('@tallstackui_autocomplete_input')
            ->click('@tallstackui_autocomplete_input')
            ->waitForText('Olinda')
            ->clickAtVisibleXPath('(//li[@dusk="tallstackui_autocomplete_option"][normalize-space(.)="Olinda"])[1]')
            ->pause(400)
            ->assertScript("document.getElementsByName('city')[0].value", 'Olinda')
            ->click('@submit')
            ->waitForText('city:')
            ->assertSee('city:Olinda'));
    }

    #[Test]
    public function submits_the_value_given_upfront(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-autocomplete')
            ->waitFor('@tallstackui_autocomplete_input')
            ->pause(500)
            ->assertScript("document.getElementsByName('state')[0].value", 'Pernambuco'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-autocomplete', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-autocomplete/result">
                <x-autocomplete name="city" :items="['Recife', 'Olinda', 'Caruaru']" clearable />
                <x-autocomplete name="state" :items="['Pernambuco', 'Bahia']" value="Pernambuco" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-autocomplete/result', fn (Request $request): string => 'city:'.$request->query('city').' state:'.$request->query('state'));
    }
}
