<?php

namespace TallStackUi\Components\Form\Tag;

use Facebook\WebDriver\WebDriverKeys;
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
    public function erasing_empties_the_submitted_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-tag')
            ->waitFor('@tags')
            ->type('@tags', 'php')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->waitFor('@tallstackui_tag_erase')
            ->click('@tallstackui_tag_erase')
            ->pause(300)
            ->assertScript("document.getElementsByName('tags')[0].value", ''));
    }

    #[Test]
    public function renders_a_single_named_input(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-tag')
            ->waitFor('@tags')
            ->assertScript("document.getElementsByName('tags').length", 1));
    }

    #[Test]
    public function submits_a_single_tag_as_a_plain_value(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-tag')
            ->waitFor('@tags')
            ->type('@tags', 'php')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->pause(300)
            ->assertScript("document.getElementsByName('tags')[0].value", 'php')
            ->click('@submit')
            ->waitForText('tags:')
            ->assertSee('tags:php'));
    }

    #[Test]
    public function submits_nothing_when_untouched(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-tag')
            ->waitFor('@tags')
            ->pause(300)
            ->assertScript("document.getElementsByName('tags')[0].value", ''));
    }

    #[Test]
    public function submits_several_tags_as_json(): void
    {
        $this->browse(fn (Browser $browser) => $browser->visit('/native-tag')
            ->waitFor('@tags')
            ->type('@tags', 'php')
            ->keys('@tags', WebDriverKeys::ENTER)
            ->pause(300)
            ->keys('@tags', 'laravel', WebDriverKeys::ENTER)
            ->pause(300)
            ->assertScript("document.getElementsByName('tags')[0].value", '["php","laravel"]')
            ->click('@submit')
            ->waitForText('tags:')
            ->assertSee('tags:["php","laravel"]'));
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/native-tag', fn (): string => Blade::render(<<<'HTML'
        <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <tallstackui:setup />
            @livewireScripts
        </head>
        <body>
            <form method="GET" action="/native-tag/result">
                <x-tag dusk="tags" name="tags" />

                <button type="submit" dusk="submit">Send</button>
            </form>
        </body>
        </html>
        HTML));

        $router->get('/native-tag/result', fn (Request $request): string => 'tags:'.$request->query('tags'));
    }
}
