<?php

namespace Tests\Browser\Form;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Form\CheckboxComponents\CheckboxComponent;
use Tests\Browser\Form\CheckboxComponents\CheckboxLiveEntangleComponent;

class CheckboxTest extends BrowserTestCase
{
    /** @test */
    public function can_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, CheckboxComponent::class)
                ->assertSee('Receive Alert')
                ->click('@entangle')
                ->click('@sync-entangle')
                ->waitForTextIn('@entangled', 'true')
                ->click('@entangle')
                ->click('@sync-entangle')
                ->waitForTextIn('@entangled', 'false');
        });
    }

    /** @test */
    public function can_live_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, CheckboxLiveEntangleComponent::class)
                ->assertSee('Receive Alert')
                ->click('@entangle')
                ->waitForTextIn('@entangled', 'true')
                ->click('@entangle')
                ->waitForTextIn('@entangled', 'false')
                ->click('@entangle')
                ->waitForTextIn('@entangled', 'true')
                ->click('@entangle')
                ->waitForTextIn('@entangled', 'false');
        });
    }
}
