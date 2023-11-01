<?php

namespace Tests\Browser\Form;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Form\InputComponents\InputComponent;
use Tests\Browser\Form\InputComponents\InputLiveEntangleComponent;

class InputTest extends BrowserTestCase
{
    /** @test */
    public function can_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, InputComponent::class)
                ->assertSee('Foo')
                ->assertSee('Bar')
                ->type('@entangle', 'foo-bar-baz')
                ->click('@sync-entangle')
                ->waitForTextIn('@entangled', 'foo-bar-baz')
                ->assertSee('foo-bar-baz');
        });
    }

    /** @test */
    public function can_live_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, InputLiveEntangleComponent::class)
                ->assertSee('Foo')
                ->assertSee('Bar')
                ->typeSlowly('@entangle-live', 'Foo bar baz')
                ->waitForTextIn('@entangled', 'Foo bar baz');
        });
    }
}
