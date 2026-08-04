<?php

namespace TallStackUi\Components\Form\Currency;

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
    public function renders_a_single_named_input(): void
    {
        // Two inputs sharing the same name would leave PHP with the last one, which
        // is the visible, locale formatted one instead of the hidden raw value.
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@input')
            ->assertScript("document.getElementsByName('price').length", 1));
    }

    #[Test]
    public function renders_the_initial_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@filled')
            ->pause(250)
            ->assertInputValue('@filled', '9.990,00'));
    }

    #[Test]
    public function submits_the_decimal_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@decimal')
            ->typeSlowly('@decimal', '123456')
            ->assertInputValue('@decimal', '1.234,56')
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('total:1234.56'));
    }

    #[Test]
    public function submits_the_raw_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-currency')
            ->waitFor('@input')
            ->typeSlowly('@input', '123456')
            ->assertInputValue('@input', '1.234,56')
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('received:123456'));
    }

    /**
     * A plain Blade page, with no Livewire component anywhere. Livewire's script is
     * still loaded because that is where Alpine comes from in a real application.
     *
     * @param  Router  $router
     */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-currency', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-currency/result">
                <x-currency dusk="input" name="price" symbol currency locale="pt-BR" />
                <x-currency dusk="decimal" name="total" decimal locale="pt-BR" />
                <x-currency dusk="filled" name="fee" value="9990" locale="pt-BR" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-currency/result', fn (Request $request): string => 'received:'.$request->query('price').' total:'.$request->query('total'));
    }
}
