<?php

namespace TallStackUi\Components\Form\Date;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert;
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
        // Two inputs sharing a name would leave PHP with the last one, and the
        // visible input carries the display format rather than the value.
        $this->browse(fn (Browser $browser) => $browser->visit('/native-date')
            ->waitFor('@single')
            ->assertScript("document.getElementsByName('published_at').length", 1));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        // An untouched picker must not invent a value for the server.
        $this->browse(fn (Browser $browser) => $browser->visit('/native-date')
            ->waitFor('@single')
            ->pause(500)
            ->assertScript("document.getElementsByName('published_at')[0].value", ''));
    }

    #[Test]
    public function submits_the_selected_date(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/native-date')
                ->waitFor('@single')
                ->click('@single')
                ->pause(500)
                ->clickAtVisibleXPath('(//div[@data-floating])[1]/div[3]/div[15]/button')
                ->pause(500);

            $hidden = $browser->script("return document.getElementsByName('published_at')[0].value;")[0];

            Assert::assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                $hidden,
                "the hidden input carries [{$hidden}] instead of a Y-m-d date",
            );

            $browser->click('@submit')
                ->waitForText('published_at:')
                ->assertSee('published_at:'.$hidden);
        });
    }

    #[Test]
    public function submits_the_value_given_upfront(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-date')
            ->waitFor('@filled')
            ->pause(500)
            ->click('@submit')
            ->waitForText('expires_at:2026-03-15')
            ->assertSee('expires_at:2026-03-15'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-date', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-date/result">
                <x-date dusk="single" name="published_at" />
                <x-date dusk="filled" name="expires_at" value="2026-03-15" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-date/result', fn (Request $request): string => 'published_at:'.$request->query('published_at').' expires_at:'.$request->query('expires_at'));
    }
}
