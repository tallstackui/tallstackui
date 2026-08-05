<?php

namespace TallStackUi\Components\QrCode;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_copy_as_a_raster(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" copy />
                    <p dusk="written"></p>
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_qr_code')
            // The real clipboard needs a permission the driver does not grant,
            // and what matters here is the type handed to it.
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
            Object.defineProperty(navigator, 'clipboard', {
                configurable: true,
                value: {
                    write: async (items) => {
                        document.querySelector('[dusk="written"]').textContent = items[0].types.join(',');
                    },
                },
            });
            JS))
            ->click('@tallstackui_qr_code_copy')
            ->waitForTextIn('@written', 'image/png')
            ->assertSeeIn('@written', 'image/png');
    }

    #[Test]
    public function can_download_as_a_raster(): void
    {
        $this->download(Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" download />
                    <p dusk="downloaded"></p>
                </div>
                HTML;
            }
        }), 'qr-code.png');
    }

    #[Test]
    public function can_download_as_a_vector(): void
    {
        $this->download(Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" download="svg" />
                    <p dusk="downloaded"></p>
                </div>
                HTML;
            }
        }), 'qr-code.svg');
    }

    #[Test]
    public function can_rasterize_at_the_configured_width(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" color="red" watermark="bolt" copy />
                    <p dusk="raster"></p>
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_qr_code')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
            Alpine.$data(document.querySelector('[dusk="tallstackui_qr_code"]').closest('[x-data]'))
                .raster()
                .then(async (blob) => {
                    const bitmap = await createImageBitmap(blob);

                    document.querySelector('[dusk="raster"]').textContent = `${blob.type}:${bitmap.width}`;
                });
            JS))
            ->waitForTextIn('@raster', 'image/png:1024')
            ->assertSeeIn('@raster', 'image/png:1024');
    }

    #[Test]
    public function can_serialize_without_depending_on_the_page(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" color="red" copy />
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_qr_code')
            ->tap(function (Browser $browser): void {
                $svg = $browser->script(<<<'JS'
                return Alpine.$data(document.querySelector('[dusk="tallstackui_qr_code"]').closest('[x-data]')).serialize();
                JS)[0];

                // The exported file is rendered without the stylesheet, so the
                // color the class resolved to has to travel with it.
                $this->assertStringContainsString('xmlns="http://www.w3.org/2000/svg"', $svg);
                $this->assertStringContainsString('style="color:', $svg);
                $this->assertStringContainsString('width="1024"', $svg);
                $this->assertStringNotContainsString('text-red-600', $svg);
            });
    }

    #[Test]
    public function can_warn_when_the_clipboard_is_unavailable(): void
    {
        Livewire::visit(new class extends Component
        {
            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-qr-code link="https://tallstackui.com" copy />
                    <p dusk="copied">no</p>
                </div>
                HTML;
            }
        })
            ->waitFor('@tallstackui_qr_code')
            ->tap(fn (Browser $browser) => $browser->script('Object.defineProperty(navigator, "clipboard", { value: undefined, configurable: true });'))
            ->click('@tallstackui_qr_code_copy')
            ->pause(500)
            ->assertSeeIn('@copied', 'no');
    }

    private function download(Browser $browser, string $expected): void
    {
        $browser->waitFor('@tallstackui_qr_code')
            // The driver has nowhere to put a file, so the anchor is caught
            // before it reaches the browser's own download handling.
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
            HTMLAnchorElement.prototype.click = function () {
                document.querySelector('[dusk="downloaded"]').textContent = this.download;
            };
            JS))
            ->click('@tallstackui_qr_code_download')
            ->waitForTextIn('@downloaded', $expected)
            ->assertSeeIn('@downloaded', $expected);
    }
}
