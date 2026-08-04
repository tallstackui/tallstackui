<?php

namespace TallStackUi\Components\Form\Color;

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
        $this->browse(fn (Browser $browser) => $browser->visit('/native-color')
            ->waitFor('@palette')
            ->assertScript("document.getElementsByName('brand').length", 1));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-color')
            ->waitFor('@palette')
            ->pause(500)
            ->assertScript("document.getElementsByName('brand')[0].value", ''));
    }

    #[Test]
    public function submits_the_picked_color(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/native-color')
                ->waitFor('@palette')
                ->click('@tallstackui_form_color_open_close')
                ->waitFor('@tallstackui_form_color_floating')
                ->pause(300)
                ->clickAtVisibleXPath('(//div[@dusk="tallstackui_form_color_floating"]//button)[1]')
                ->pause(300);

            $hidden = $browser->script("return document.getElementsByName('brand')[0].value;")[0];

            Assert::assertNotSame('', $hidden, 'picking a swatch must fill the hidden input');

            $browser->click('@submit')
                ->waitForText('brand:')
                ->assertSee('brand:'.$hidden);
        });
    }

    #[Test]
    public function submits_the_value_given_upfront(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-color')
            ->waitFor('@filled')
            ->pause(500)
            ->click('@submit')
            ->waitForText('accent:')
            ->assertSee('accent:#ff0000'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-color', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-color/result">
                <x-color dusk="palette" name="brand" />
                <x-color dusk="filled" name="accent" value="#ff0000" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-color/result', fn (Request $request): string => 'brand:'.$request->query('brand').' accent:'.$request->query('accent'));
    }
}
