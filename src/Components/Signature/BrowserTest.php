<?php

namespace TallStackUi\Components\Signature;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
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
    public function cannot_set_height_less_than_10(): void
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
    public function cannot_set_line_as_null(): void
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

    #[Test]
    public function keeps_the_drawing_when_the_window_is_resized(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $signature = null;

            public function render(): string
            {
                return <<<'HTML'
                    <div>
                        <x-signature wire:model="signature" clearable />
                    </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->resize(1400, 900)
            ->pause(250)
            ->dragRight('@tallstackui_signature_canva', 120)
            ->dragUp('@tallstackui_signature_canva', 60)
            ->pause(250)
            ->tap(function (Browser $browser): void {
                $before = $this->ink($browser);

                Assert::assertGreaterThan(0, $before, 'the drag should have drawn something on the canvas');

                $browser->resize(900, 900)->pause(500);

                Assert::assertGreaterThan(0, $this->ink($browser), 'resizing must not wipe the drawing');
            });
    }

    #[Test]
    public function keeps_the_model_null_when_resized_without_drawing(): void
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
            ->resize(1400, 900)
            ->pause(250)
            ->resize(900, 900)
            ->pause(500)
            ->waitForLivewire()->click('@sync')
            // An untouched canvas must not start reporting a blank data URL.
            ->assertNotPresent('@signature');
    }

    /** Non-background pixels currently painted on the signature canvas. */
    private function ink(Browser $browser): int
    {
        return $browser->script(
            "const canvas = document.querySelector('[dusk=tallstackui_signature_canva]');
             const data = canvas.getContext('2d').getImageData(0, 0, canvas.width, canvas.height).data;

             let count = 0;

             for (let index = 0; index < data.length; index += 4) {
                if (data[index] !== 0 || data[index + 1] !== 0 || data[index + 2] !== 0) {
                    continue;
                }

                if (data[index + 3] > 0) {
                    count++;
                }
             }

             return count;"
        )[0];
    }
}
