<?php

namespace Tests\Browser\Alert;

use Livewire\Component;
use Laravel\Dusk\Browser;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_close()
    {
        $this->browse(
            fn (Browser $browser) => $this
                ->visit($browser, AlertComponent::class)
                ->assertSee('Foo bar')
                ->click('#close')
                ->pause(1000)
                ->assertDontSee('Foo bar')
        );
    }
}

class AlertComponent extends Component
{
    public function render(): string
    {
        return <<<HTML
        <div>
            <x-alert closeable>Foo bar</x-alert>
        </div>
HTML;
    }
}
