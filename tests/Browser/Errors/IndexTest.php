<?php

namespace Tests\Browser\Errors;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Errors\Components\ErrorColoredComponent;
use Tests\Browser\Errors\Components\ErrorComponent;
use Tests\Browser\Errors\Components\ErrorOnlyComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_render(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ErrorComponent::class)
                ->assertSee('Save')
                ->assertDontSee('There are 1 validation errors:')
                ->click('#save')
                ->pause(500)
                ->assertSee('There are 1 validation errors:');

            $this->assertNotNull($browser->element('.bg-red-50'));
        });
    }

    /** @test */
    public function can_render_colored(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ErrorColoredComponent::class)
                ->assertSee('Save')
                ->assertDontSee('There are 1 validation errors:')
                ->click('#save')
                ->pause(500)
                ->assertSee('There are 1 validation errors:');

            $this->assertNotNull($browser->element('.bg-pink-50'));
        });
    }

    /** @test */
    public function can_render_only_selecteds(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, ErrorOnlyComponent::class)
                ->assertSee('Save')
                ->assertDontSee('description')
                ->assertDontSee('name')
                ->click('#save')
                ->pause(500)
                ->assertSee('There are 1 validation errors:')
                ->assertSee('name')
                ->assertDontSee('description');
        });
    }
}
