<?php

namespace Tests\Browser\Form;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Form\RadioComponents\RadioComponent;
use Tests\Browser\Form\RadioComponents\RadioLiveEntangleComponent;

class RadioTest extends BrowserTestCase
{
    /** @test */
    public function can_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, RadioComponent::class)
                ->assertSee('Receive Alert')
                ->click('@entangle-true')
                ->click('@sync-entangle')
                ->waitForTextIn('@entangled', 'true')
                ->click('@entangle-false')
                ->click('@sync-entangle')
                ->waitForTextIn('@entangled', 'false');
        });
    }

    /** @test */
    public function can_live_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, RadioLiveEntangleComponent::class)
                ->assertSee('Receive Alert')
                ->click('@entangle-true')
                ->waitForTextIn('@entangled', 'true')
                ->click('@entangle-false')
                ->waitForTextIn('@entangled', 'false');
        });
    }
}
