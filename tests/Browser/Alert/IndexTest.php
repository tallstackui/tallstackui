<?php

namespace Tests\Browser\Alert;

use Laravel\Dusk\Browser;
use Tests\Browser\Alert\Components\AlertCommonPersonalizedComponent;
use Tests\Browser\Alert\Components\AlertComponent;
use Tests\Browser\Alert\Components\AlertCustomPersonalizedComponent;
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

    /** @test */
    public function can_personalize(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertCommonPersonalizedComponent::class);
            $this->assertNotNull($browser->element('.bg-red-500'));
            $this->assertNull($browser->element('.bg-primary-500'));
        });
    }

    /** @test */
    public function can_custom_personalize(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertCustomPersonalizedComponent::class);
            $this->assertNotNull($browser->element('.bg-red-500'));
            $this->assertNull($browser->element('.bg-primary-500'));
        });
    }
}
