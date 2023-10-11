<?php

namespace Tests\Browser\Dropdown;

use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;
use Tests\Browser\Dropdown\Components\DropdownActionComponent;
use Tests\Browser\Dropdown\Components\DropdownIconComponent;
use Tests\Browser\Dropdown\Components\DropdownTitleComponent;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_render_with_title(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DropdownTitleComponent::class)
                ->assertSee('FooBar')
                ->click('@open-dropdown')
                ->waitForText('Lorem')
                ->waitForText('Ipsum')
                ->click('@open-dropdown')
                ->waitUntilMissingText('Lorem')
                ->waitUntilMissingText('Ipsum');
        });
    }

    /** @test */
    public function can_render_with_icon(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DropdownIconComponent::class)
                ->click('@open-dropdown')
                ->waitForText('Lorem')
                ->waitForText('Ipsum')
                ->assertSee('Lorem')
                ->assertSee('Ipsum')
                ->click('@open-dropdown')
                ->waitUntilMissingText('Lorem')
                ->waitUntilMissingText('Ipsum');
        });
    }

    /** @test */
    public function can_render_with_action(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, DropdownActionComponent::class)
                ->assertSee('FooBar')
                ->click('#action')
                ->waitForText('Lorem')
                ->waitForText('Ipsum')
                ->click('#action')
                ->waitUntilMissingText('Lorem')
                ->waitUntilMissingText('Ipsum');
        });
    }
}
