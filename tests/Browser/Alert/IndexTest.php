<?php

namespace Tests\Browser\Alert;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_close(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-alert closeable>Foo bar</x-alert>
                    </div>
                HTML;
            }
        })
            ->assertSee('Foo bar')
            ->click('@alert-close-button')
            ->waitUntilMissingText('Foo bar');
    }
}
