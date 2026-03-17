<?php

namespace TallStackUi\Components\Alert;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
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

    #[Test]
    public function can_dismiss_after_timeout(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-alert :dismiss="2">Auto dismiss alert</x-alert>
                    </div>
                HTML;
            }
        })
            ->assertSee('Auto dismiss alert')
            ->waitUntilMissingText('Auto dismiss alert');
    }

    #[Test]
    public function can_dismiss_with_close_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-alert :dismiss="10" close>Dismiss with close</x-alert>
                    </div>
                HTML;
            }
        })
            ->assertSee('Dismiss with close')
            ->click('@alert-close-button')
            ->waitUntilMissingText('Dismiss with close');
    }
}
