<?php

namespace TallStackUi\Components\Switcher;

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
    public function can_emit_switch_event(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-switcher')
            ->waitFor('@tallstackui_switcher')
            ->pause(300)
            ->assertSeeIn('@event', 'silent')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@event', 'true'));
    }

    #[Test]
    public function can_pair_with_alpine_model(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-switcher')
            ->waitFor('@tallstackui_switcher')
            ->pause(300)
            ->assertSeeIn('@model', 'false')
            ->click('@tallstackui_switcher')
            ->waitForTextIn('@model', 'true')
            ->click('@outside')
            ->waitForTextIn('@model', 'false')
            ->waitFor('@tallstackui_switcher_off')
            ->waitUntilMissing('@tallstackui_switcher_on'));
    }

    #[Test]
    public function can_start_checked(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-switcher/checked')
            ->waitFor('@tallstackui_switcher_on')
            ->assertMissing('@tallstackui_switcher_off')
            ->click('@submit')
            ->waitForText('notify:')
            ->assertSee('notify:yes'));
    }

    #[Test]
    public function can_submit_the_value_when_on(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-switcher')
            ->waitFor('@tallstackui_switcher')
            ->pause(300)
            ->click('@tallstackui_switcher')
            ->waitFor('@tallstackui_switcher_on')
            ->click('@submit')
            ->waitForText('notify:')
            ->assertSee('notify:yes'));
    }

    #[Test]
    public function cannot_submit_the_value_when_off(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-switcher')
            ->waitFor('@tallstackui_switcher')
            ->pause(300)
            ->click('@submit')
            ->waitForText('notify:')
            ->assertSee('notify:missing'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-switcher/result', fn (Request $request): string => 'notify:'.$request->query('notify', 'missing'));

        $router->get('/native-switcher/{state?}', fn (?string $state = null): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-switcher/result" x-data="{ notify: @js($checked), event: 'silent' }">
                <p dusk="model" x-text="notify"></p>

                <p dusk="event" x-text="event"></p>

                <x-switcher name="notify"
                            value="yes"
                            on="Enabled"
                            off="Muted"
                            x-model="notify"
                            x-on:switch="event = $event.detail.value"
                            :$checked />

                <button type="button" dusk="outside" x-on:click="notify = false">Reset</button>

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML, ['checked' => $state === 'checked']));
    }
}
