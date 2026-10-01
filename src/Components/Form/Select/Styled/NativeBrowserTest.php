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
    public function can_keep_a_value_with_a_comma_selected_after_the_round_trip(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-select/round-trip/person')
            ->waitFor('@submit')
            ->waitForTextIn('@field', 'Silva, João')
            ->click('@submit')
            ->waitFor('@submitted')
            ->waitForTextIn('@field', 'Silva, João')
            ->assertDontSeeIn('@field', 'Select an option'));
    }

    #[Test]
    public function can_select_an_integer_option_from_a_zero_padded_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-select/round-trip/month')
            ->waitFor('@submit')
            ->waitForTextIn('@field', 'February')
            ->click('@submit')
            ->waitFor('@submitted')
            ->waitForTextIn('@field', 'February')
            ->assertScript("document.getElementsByName('value')[0].value", '2'));
    }

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

        // Hands the submitted value back to the field, the way `old()` does
        // after a failed validation.
        $router->get('/native-select/round-trip/{field}', fn (string $field): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET">
                <div dusk="field">
                    <x-select.styled name="value" :$options select="label:label|value:value" :value="request('value', $default)" />
                </div>

                <button type="submit" dusk="submit">Send</button>
            </form>

            @if (request()->has('value'))
                <p dusk="submitted">submitted</p>
            @endif
        </body>
        </html>
        HTML, $field === 'month' ? [
            'default' => '02',
            'options' => [['label' => 'January', 'value' => 1], ['label' => 'February', 'value' => 2]],
        ] : [
            'default' => 'Silva, João',
            'options' => [['label' => 'Silva, João', 'value' => 'Silva, João'], ['label' => 'Souza, Ana', 'value' => 'Souza, Ana']],
        ]));
    }
}
