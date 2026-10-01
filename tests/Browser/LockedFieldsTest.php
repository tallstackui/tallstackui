<?php

namespace Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * The fields that keep their value in a hidden input, outside Livewire, on a
 * plain Blade page submitted to a controller. Livewire's script is loaded only
 * because that is where Alpine comes from in a real application.
 */
class LockedFieldsTest extends BrowserTestCase
{
    #[Test]
    public function can_submit_readonly_fields(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/locked-fields/readonly')
            ->waitFor('@submit')
            ->pause(500)
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('received:email,plan,tags,city,date,time,price,color,code'));
    }

    #[Test]
    public function cannot_submit_disabled_fields(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/locked-fields/disabled')
            ->waitFor('@submit')
            ->pause(500)
            ->click('@submit')
            ->waitForText('received:')
            ->assertSee('received:email')
            ->assertDontSee('received:email,'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/locked-fields/result', fn (Request $request): string => 'received:'.implode(',', array_keys($request->query())));

        $router->get('/locked-fields/{lock}', fn (string $lock): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/locked-fields/result">
                <x-input name="email" value="taylor@laravel.com" />
                <x-select.styled name="plan" :options="['basic', 'pro']" value="pro" :disabled="$disabled" :readonly="$readonly" />
                <x-tag name="tags" :value="['php', 'laravel']" :disabled="$disabled" :readonly="$readonly" />
                <x-autocomplete name="city" :items="[['value' => 'Lisbon'], ['value' => 'Porto']]" value="Porto" :disabled="$disabled" :readonly="$readonly" />
                <x-date name="date" value="2026-10-01" :disabled="$disabled" :readonly="$readonly" />
                <x-time name="time" value="10:30" format="24" :disabled="$disabled" :readonly="$readonly" />
                <x-currency name="price" value="1500" :disabled="$disabled" :readonly="$readonly" />
                <x-color name="color" value="#ff0000" :disabled="$disabled" :readonly="$readonly" />
                <x-pin name="code" :length="4" value="1234" :disabled="$disabled" :readonly="$readonly" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML, ['disabled' => $lock === 'disabled', 'readonly' => $lock === 'readonly']));
    }
}
