<?php

namespace Tests\Browser\Interactions\Toast;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Interactions\Toast\Components\ToastComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_send(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ToastComponent::class)
                ->assertDontSee('Foo bar success')
                ->click('#success')
                ->waitForText('Foo bar success')
                ->assertDontSee('Foo bar error')
                ->click('#error')
                ->waitForText('Foo bar error')
                ->assertDontSee('Foo bar info')
                ->click('#info')
                ->waitForText('Foo bar info')
                ->assertDontSee('Foo bar warning')
                ->click('#warning')
                ->waitForText('Foo bar warning');
        });
    }

    /** @test */
    public function can_send_cancellation(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ToastComponent::class)
                ->assertDontSee('Foo bar confirmation description')
                ->click('#confirm')
                ->waitForText('Foo bar confirmation description')
                ->click('@tallstackui_toast_rejection')
                ->waitForText('Bar foo cancelled bar');
        });
    }

    /** @test */
    public function can_send_confirmation(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ToastComponent::class)
                ->assertDontSee('Foo bar confirmation description')
                ->click('#confirm')
                ->waitForText('Foo bar confirmation description')
                ->click('@tallstackui_toast_confirmation')
                ->waitForText('Foo bar confirmed foo');
        });
    }
}
