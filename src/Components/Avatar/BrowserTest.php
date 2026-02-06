<?php

namespace Tests\Browser\Avatar;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    #[Test]
    public function can_bind_src(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div x-data="{image: 'https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png'}">
                    <x-avatar image x-bind:src="image" />
                </div>
                HTML;
            }
        })
            ->waitFor('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]')
            ->assertVisible('img[src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"]');
    }
}
