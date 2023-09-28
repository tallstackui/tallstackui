<?php

namespace Tests\Browser\Modal;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Modal\Components\ModalComponent;
use Tests\Browser\Modal\Components\ModalComponentDifferentEntangle;
use Tests\Browser\Modal\Components\ModalComponentNotEntangled;
use Tests\Browser\Modal\Components\ModalComponentWithTitle;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_open(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ModalComponent::class)
                ->assertSee('Open')
                ->assertDontSee('Foo bar')
                ->click('#open')
                ->pause(50)
                ->assertSee('Foo bar');
        });
    }

    /** @test */
    public function can_open_and_see_title(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ModalComponentWithTitle::class)
                ->assertSee('Open')
                ->assertDontSee('Foo bar')
                ->assertDontSee('Bar baz')
                ->click('#open')
                ->pause(50)
                ->assertSee('Foo bar')
                ->assertSee('Bar baz');
        });
    }

    /** @test */
    public function can_open_using_helper(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ModalComponentNotEntangled::class)
                ->assertSee('Open')
                ->assertDontSee('Foo bar')
                ->click('#open')
                ->pause(50)
                ->assertSee('Foo bar');
        });
    }

    /** @test */
    public function can_open_using_different_entangle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ModalComponentDifferentEntangle::class)
                ->assertSee('Open')
                ->assertDontSee('Foo bar')
                ->click('#open')
                ->pause(50)
                ->assertSee('Foo bar');
        });
    }
}
