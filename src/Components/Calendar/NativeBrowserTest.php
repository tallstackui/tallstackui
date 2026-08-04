<?php

namespace TallStackUi\Components\Calendar;

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
        $this->browse(fn (Browser $browser) => $browser->visit('/native-calendar')
            ->waitFor('@tallstackui_calendar')
            ->assertScript("document.getElementsByName('scheduled_at').length", 1));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-calendar')
            ->waitFor('@tallstackui_calendar')
            ->pause(500)
            ->assertScript("document.getElementsByName('scheduled_at')[0].value", ''));
    }

    #[Test]
    public function submits_the_selected_date(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/native-calendar')
                ->waitFor('@tallstackui_calendar')
                ->pause(500)
                ->clickAtVisibleXPath('(//div[@dusk="tallstackui_calendar"]//button[normalize-space(text())="15"])[1]')
                ->pause(500);

            $hidden = $browser->script("return document.getElementsByName('scheduled_at')[0].value;")[0];

            Assert::assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                $hidden,
                "the hidden input carries [{$hidden}] instead of a Y-m-d date",
            );

            $browser->click('@submit')
                ->waitForText('scheduled_at:')
                ->assertSee('scheduled_at:'.$hidden);
        });
    }

    #[Test]
    public function submits_the_value_given_upfront(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-calendar')
            ->waitFor('@tallstackui_calendar')
            ->pause(500)
            ->assertScript("document.getElementsByName('expires_at')[0].value", '2026-03-15'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-calendar', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-calendar/result">
                <x-calendar name="scheduled_at" />
                <x-calendar name="expires_at" value="2026-03-15" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-calendar/result', fn (Request $request): string => 'scheduled_at:'.$request->query('scheduled_at').' expires_at:'.$request->query('expires_at'));
    }
}
