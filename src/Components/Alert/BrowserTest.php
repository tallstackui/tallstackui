<?php

namespace Tests\Browser\Alert;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    #[Test]
    public function can_close(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-alert close>Foo bar</x-alert>
                    </div>
                HTML;
            }
        })
            ->assertSee('Foo bar')
            ->click('@alert-close-button')
            ->waitUntilMissingText('Foo bar');
    }
}
