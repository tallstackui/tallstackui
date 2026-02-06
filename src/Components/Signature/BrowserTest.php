<?php

namespace TallStackUi\Components\Signature;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_clear(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        @if ($signature)
                            <p dusk="signature">{!! $signature !!}</p>
                        @endif

                        <x-signature wire:model="signature" clearable />

                        <x-button dusk="sync" wire:click="sync">Sync</x-button>
                    </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForLivewireToLoad()
            ->dragRight('@tallstackui_signature_canva', 100)
            ->dragLeft('@tallstackui_signature_canva', 200)
            ->dragUp('@tallstackui_signature_canva', 50)
            ->waitForLivewire()
            ->click('@sync')
            ->waitForTextIn('@signature', 'data:image/png;base64,')
            ->click('@tallstackui_signature_clear')
            ->assertPresent('@signature')
            ->waitForLivewire()
            ->click('@sync')
            ->assertNotPresent('@signature');
    }

    #[Test]
    public function can_draw(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <p dusk="signature">{!! $signature !!}</p>

                        <x-signature wire:model="signature" />

                        <x-button dusk="sync" wire:click="sync">Sync</x-button>
                    </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForLivewireToLoad()
            ->dragRight('@tallstackui_signature_canva', 100)
            ->dragLeft('@tallstackui_signature_canva', 200)
            ->click('@sync')
            ->waitForTextIn('@signature', 'data:image/png;base64,');
    }

    #[Test]
    public function can_redo(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <p dusk="signature">{!! $signature !!}</p>

                        <x-signature wire:model="signature" />

                        <x-button dusk="sync" wire:click="sync">Sync</x-button>
                    </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForLivewireToLoad()
            ->dragRight('@tallstackui_signature_canva', 100)
            ->dragLeft('@tallstackui_signature_canva', 200)
            ->dragUp('@tallstackui_signature_canva', 50)
            ->waitForLivewire()
            ->click('@sync')
            ->waitForTextIn('@signature', 'data:image/png;base64,');

        $fullSignature = $browser->text('@signature');

        $browser
            ->click('@tallstackui_signature_undo')
            ->waitForLivewire()->click('@sync');

        $undoneSignature = $browser->text('@signature');

        $this->assertNotEquals($fullSignature, $undoneSignature);

        $browser
            ->click('@tallstackui_signature_redo')
            ->waitForLivewire()->click('@sync');

        $redoneSignature = $browser->text('@signature');

        $this->assertStringStartsWith('data:image/png;base64,', $redoneSignature);
        $this->assertNotEquals($undoneSignature, $redoneSignature);
    }

    #[Test]
    public function can_undo(): void
    {
        $browser = Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <p dusk="signature">{!! $signature !!}</p>

                        <x-signature wire:model="signature" />

                        <x-button dusk="sync" wire:click="sync">Sync</x-button>
                    </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->waitForLivewireToLoad()
            ->dragRight('@tallstackui_signature_canva', 100)
            ->dragLeft('@tallstackui_signature_canva', 200)
            ->dragUp('@tallstackui_signature_canva', 50)
            ->waitForLivewire()
            ->click('@sync')
            ->waitForTextIn('@signature', 'data:image/png;base64,');

        $fullSignature = $browser->text('@signature');

        $browser
            ->click('@tallstackui_signature_undo')
            ->waitForLivewire()->click('@sync');

        $undoneSignature = $browser->text('@signature');

        $this->assertStringStartsWith('data:image/png;base64,', $undoneSignature);
        $this->assertNotEquals($fullSignature, $undoneSignature);
    }

    #[Test]
    public function cannot_set_height_less_than_10()
    {
        Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-signature :height="9" />
                    </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Signature: The [height] must be at least 10.');
    }

    #[Test]
    public function cannot_set_line_as_null()
    {
        Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-signature :line="null" />
                    </div>
                HTML;
            }
        })
            ->assertSee('[TallStackUI] Signature: The [line] must be a number.');
    }
}
