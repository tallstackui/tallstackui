<?php

namespace Tests\Browser\Alert;

use Laravel\Dusk\Browser;
use Tests\Browser\Alert\Components\AlertComponent;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_close(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertComponent::class)
                ->assertSee('Foo bar')
                ->click('#close')
                ->waitUntilMissingText('Foo bar');
        });
    }
}
