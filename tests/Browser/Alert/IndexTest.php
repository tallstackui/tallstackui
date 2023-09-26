<?php

namespace Tests\Browser\Alert;

use Laravel\Dusk\Browser;
use Livewire\Component;
use TasteUi\Facades\TasteUi;
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
                ->pause(500)
                ->assertDontSee('Foo bar');
        });
    }

    /** @test */
    public function can_personalize(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertPersonalizedComponent::class);
            $this->assertNotNull($browser->element('.bg-red-500'));
            $this->assertNull($browser->element('.bg-primary-500'));
        });
    }
}

class AlertComponent extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-alert closeable>Foo bar</x-alert>
        </div>
HTML;
    }
}

class AlertPersonalizedComponent extends Component
{
    public function render(): string
    {
        TasteUi::personalization('taste-ui::personalizations.alert')
            ->block('main', fn () => 'rounded-md p-6 bg-red-500');

        return <<<'HTML'
        <div>
            <x-alert>Foo bar</x-alert>
        </div>
HTML;
    }
}
