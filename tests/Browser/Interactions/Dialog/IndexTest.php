<?php

namespace Tests\Browser\Interactions\Dialog;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Interactions\Dialog\Components\DialogComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_send(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DialogComponent::class)
                ->assertDontSee('Foo bar success')
                ->click('#success')
                ->waitForText('Foo bar success')
                ->click('#tasteui_dialog_confirmation')
                ->assertDontSee('Foo bar error')
                ->click('#error')
                ->waitForText('Foo bar error')
                ->click('#tasteui_dialog_confirmation')
                ->assertDontSee('Foo bar info')
                ->click('#info')
                ->waitForText('Foo bar info')
                ->click('#tasteui_dialog_confirmation')
                ->assertDontSee('Foo bar warning')
                ->click('#warning')
                ->waitForText('Foo bar warning')
                ->click('#tasteui_dialog_confirmation');
        });
    }

    /** @test */
    public function can_send_confirmation(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DialogComponent::class)
                ->assertDontSee('Foo bar confirmation description')
                ->click('#confirm')
                ->waitForText('Foo bar confirmation description')
                ->click('#tasteui_dialog_confirmation')
                ->waitUntilMissingText('Foo bar confirmation description');
        });
    }

    /** @test */
    public function can_send_cancellation(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DialogComponent::class)
                ->assertDontSee('Foo bar confirmation description')
                ->click('#confirm')
                ->waitForText('Foo bar confirmation description')
                ->click('#tasteui_dialog_rejection')
                ->waitForText('Bar foo cancelled bar');
        });
    }
}
