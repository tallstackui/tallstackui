<?php

namespace Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * The fields that keep their value in a hidden input, outside Livewire, when
 * the same name shows up more than once on the page: two forms holding the
 * same fields, or an array name repeated in one form.
 */
class RepeatedFieldNamesTest extends BrowserTestCase
{
    #[Test]
    public function can_submit_each_field_of_a_repeated_array_name(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/repeated-field-names')
            ->waitFor('@submit-list')
            ->pause(500)
            ->click('@submit-list')
            ->waitForText('received:')
            ->assertSee('prices=1000.00,2550.00')
            ->assertSee('codes=1234,9876'));
    }

    #[Test]
    public function can_submit_the_first_form_with_its_own_values(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/repeated-field-names')
            ->waitFor('@submit-first')
            ->pause(500)
            ->click('@submit-first')
            ->waitForText('received:')
            ->assertSee('plan=pro')
            ->assertSee('tags=["php","laravel"]')
            ->assertSee('city=Porto')
            ->assertSee('date=2026-10-01')
            ->assertSee('time=10:30')
            ->assertSee('price=1500.00')
            ->assertSee('color=#ff0000')
            ->assertSee('code=1234')
            ->assertSee('day=2026-10-01'));
    }

    #[Test]
    public function can_submit_the_second_form_with_its_own_values(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/repeated-field-names')
            ->waitFor('@submit-second')
            ->pause(500)
            ->click('@submit-second')
            ->waitForText('received:')
            ->assertSee('plan=basic')
            ->assertSee('tags=["js","vue"]')
            ->assertSee('city=Lisbon')
            ->assertSee('date=2026-11-15')
            ->assertSee('time=08:45')
            ->assertSee('price=2599.00')
            ->assertSee('color=#00ff00')
            ->assertSee('code=9876')
            ->assertSee('day=2026-12-25'));
    }

    #[Test]
    public function can_write_to_its_own_input_when_another_element_uses_the_name(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/repeated-field-names')
            ->waitFor('@submit-native')
            ->pause(500)
            ->click('@submit-native')
            ->waitForText('received:')
            ->assertSee('status=archived,active'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/repeated-field-names/result', fn (Request $request): string => 'received: '.collect($request->query())
            ->map(fn (mixed $value, string $key): string => $key.'='.implode(',', (array) $value))
            ->implode(' | '));

        $router->get('/repeated-field-names', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/repeated-field-names/result">
                <x-select.styled name="plan" :options="['basic', 'pro']" value="pro" />
                <x-tag name="tags" :value="['php', 'laravel']" />
                <x-autocomplete name="city" :items="[['value' => 'Lisbon'], ['value' => 'Porto']]" value="Porto" />
                <x-date name="date" value="2026-10-01" />
                <x-time name="time" value="10:30" format="24" />
                <x-currency name="price" value="1500" decimal />
                <x-color name="color" value="#ff0000" />
                <x-pin name="code" :length="4" value="1234" />
                <x-calendar name="day" value="2026-10-01" />

                <button type="submit" dusk="submit-first">Send</button>
            </form>

            <form method="GET" action="/repeated-field-names/result">
                <x-select.styled name="plan" :options="['basic', 'pro']" value="basic" />
                <x-tag name="tags" :value="['js', 'vue']" />
                <x-autocomplete name="city" :items="[['value' => 'Lisbon'], ['value' => 'Porto']]" value="Lisbon" />
                <x-date name="date" value="2026-11-15" />
                <x-time name="time" value="08:45" format="24" />
                <x-currency name="price" value="2599" decimal />
                <x-color name="color" value="#00ff00" />
                <x-pin name="code" :length="4" value="9876" />
                <x-calendar name="day" value="2026-12-25" />

                <button type="submit" dusk="submit-second">Send</button>
            </form>

            <form method="GET" action="/repeated-field-names/result">
                <x-currency name="prices[]" value="1000" decimal />
                <x-currency name="prices[]" value="2550" decimal />
                <x-pin name="codes[]" :length="4" value="1234" />
                <x-pin name="codes[]" :length="4" value="9876" />

                <button type="submit" dusk="submit-list">Send</button>
            </form>

            <form method="GET" action="/repeated-field-names/result">
                <select name="status[]">
                    <option value="archived" selected>Archived</option>
                </select>
                <x-select.styled name="status[]" :options="['active', 'paused']" value="active" />

                <button type="submit" dusk="submit-native">Send</button>
            </form>
        </body>
        </html>
        HTML));
    }
}
