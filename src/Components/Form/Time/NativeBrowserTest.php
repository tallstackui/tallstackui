<?php

namespace TallStackUi\Components\Form\Time;

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
        $this->browse(fn (Browser $browser) => $browser->visit('/native-time')
            ->waitFor('@twelve')
            ->assertScript("document.getElementsByName('starts_at').length", 1));
    }

    #[Test]
    public function submits_a_twelve_hour_value(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/native-time')
                ->waitFor('@twelve')
                ->click('@twelve')
                ->waitForText('00')
                ->dragRight('@tallstackui_time_hours', 5)
                ->pause(500);

            $hidden = $browser->script("return document.getElementsByName('starts_at')[0].value;")[0];

            Assert::assertMatchesRegularExpression(
                '/^(0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/',
                $hidden,
                "the hidden input carries [{$hidden}] instead of a 12-hour time",
            );

            $browser->click('@submit')
                ->waitForText('starts_at:')
                ->assertSee('starts_at:'.$hidden);
        });
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-time')
            ->waitFor('@twelve')
            ->pause(500)
            ->assertScript("document.getElementsByName('starts_at')[0].value", ''));
    }

    #[Test]
    public function submits_the_value_given_upfront(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-time')
            ->waitFor('@filled')
            ->pause(500)
            ->click('@submit')
            ->waitForText('ends_at:23:30')
            ->assertSee('ends_at:23:30'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-time', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-time/result">
                <x-time dusk="twelve" name="starts_at" />
                <x-time dusk="filled" name="ends_at" format="24" value="23:30" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-time/result', fn (Request $request): string => 'starts_at:'.$request->query('starts_at').' ends_at:'.$request->query('ends_at'));
    }
}
