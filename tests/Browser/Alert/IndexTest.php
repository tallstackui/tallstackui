<?php

namespace Tests\Browser\Alert;

use Laravel\Dusk\Browser;
use Livewire\Component;
use TasteUi\Contracts\Personalizable;
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
    public function can_make_common_personalization(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertCommonPersonalizedComponent::class);
            $this->assertNotNull($browser->element('.bg-red-500'));
            $this->assertNull($browser->element('.bg-primary-500'));
        });
    }

    /** @test */
    public function can_make_custom_personalization(): void
    {
        $this->browse(function (Browser $browser) {
            $this->visit($browser, AlertCustomPersonalizedComponent::class);
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

class AlertCommonPersonalizedComponent extends Component
{
    public function render(): string
    {
        TasteUi::personalization('taste-ui::personalizations.alert')
            ->block('base', fn () => 'rounded-md p-6 bg-red-500');

        return <<<'HTML'
        <div>
            <x-alert>Foo bar</x-alert>
        </div>
HTML;
    }
}

class Personalize implements Personalizable
{
    public function __invoke(array $data): string
    {
        return 'rounded-md p-6 bg-red-500';
    }
}

class AlertCustomPersonalizedComponent extends Component
{
    public function render(): string
    {
        TasteUi::personalization('taste-ui::personalizations.alert')
            ->block('base', new Personalize());

        return <<<'HTML'
        <div>
            <x-alert>Foo bar</x-alert>
        </div>
HTML;
    }
}
