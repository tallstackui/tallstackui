<?php

namespace TallStackUi\Components\Form\Select\Styled;

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
    public function submits_a_zero_value(): void
    {
        // 0 is a legitimate option value, and the hidden input used to receive
        // an empty string for it because the setter tested truthiness.
        $this->browse(fn (Browser $browser) => $browser->visit('/native-select')
            ->waitFor('@tallstackui_select_open_close')
            ->click('@tallstackui_select_open_close')
            ->waitForText('Inactive')
            ->clickAtXPath("//li[contains(., 'Inactive')]")
            ->pause(250)
            ->assertScript("document.getElementsByName('status')[0].value", '0')
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('received:0'));
    }

    /**
     * A plain Blade page with no Livewire component. Livewire's script still
     * loads because that is where Alpine comes from in a real application.
     *
     * @param  Router  $router
     */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-select', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-select/result">
                <x-select.styled name="status"
                                 :options="[['label' => 'Inactive', 'value' => 0], ['label' => 'Active', 'value' => 1]]"
                                 select="label:label|value:value" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-select/result', fn (Request $request): string => 'received:'.$request->query('status'));
    }
}
