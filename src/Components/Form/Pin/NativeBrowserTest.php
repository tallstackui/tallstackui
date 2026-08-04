<?php

namespace TallStackUi\Components\Form\Pin;

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
        $this->browse(fn (Browser $browser) => $browser->visit('/native-pin')
            ->waitFor('@pin-1')
            ->type('@pin-1', '1')
            ->type('@pin-2', '2')
            ->type('@pin-3', '3')
            ->type('@pin-4', '4')
            ->pause(300)
            ->click('@form_pin_clear')
            ->pause(300)
            ->assertScript("document.getElementsByName('code')[0].value", ''));
    }

    #[Test]
    public function renders_a_single_named_input(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-pin')
            ->waitFor('@pin-1')
            ->assertScript("document.getElementsByName('code').length", 1));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-pin')
            ->waitFor('@pin-1')
            ->pause(300)
            ->assertScript("document.getElementsByName('code')[0].value", ''));
    }

    #[Test]
    public function submits_the_typed_code(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-pin')
            ->waitFor('@pin-1')
            ->type('@pin-1', '1')
            ->type('@pin-2', '2')
            ->type('@pin-3', '3')
            ->type('@pin-4', '4')
            ->pause(300)
            ->click('@submit')
            ->waitForText('code:')
            ->assertSee('code:1234'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-pin', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-pin/result">
                <x-pin name="code" :length="4" clear />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-pin/result', fn (Request $request): string => 'code:'.$request->query('code'));
    }
}
