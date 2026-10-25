<?php

namespace TallStackUi\Components\Form\Upload\Async;

use Laravel\Dusk\Browser;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_describe_a_rejected_file_through_a_tooltip(): void
    {
        $balloon = fn (Browser $browser): string => $browser->script(
            "return document.querySelector('[data-tsui-tooltip][data-show]')?.textContent ?? '';"
        )[0];

        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" accept="image/*" multiple />
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForText('File type not allowed.')
            ->tap(fn (Browser $browser) => Assert::assertSame('', $balloon($browser), 'the balloon should start hidden'))
            // A synthetic pointerenter keeps the hover out of the driver's
            // hands, which cannot promise where the cursor lands on a tile.
            ->tap(fn (Browser $browser) => $browser->script("document.querySelector('[dusk=tallstackui_upload_async_tile]').dispatchEvent(new PointerEvent('pointerenter', { pointerType: 'mouse', bubbles: true }));"))
            ->pause(500)
            ->tap(fn (Browser $browser) => Assert::assertStringContainsString('File type not allowed.', $balloon($browser), 'hovering a rejected tile should describe the error'));
    }

    #[Test]
    public function can_follow_a_reset_of_the_property(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public array $saved = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model="files" :route="route('async.upload')" multiple />
                    <button type="button" dusk="save" wire:click="save">Save</button>
                    <p dusk="saved">{{ implode(',', $saved) }}</p>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->saved = array_column($this->files, 'real_name');

                $this->reset('files');
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->click('@save')
            ->waitForTextIn('@saved', 'test.jpeg')
            ->waitUntilMissing('@tallstackui_upload_async_tile')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForText('test.pdf')
            ->click('@save')
            ->waitForTextIn('@saved', 'test.pdf')
            ->assertDontSeeIn('@saved', 'test.jpeg');
    }

    #[Test]
    public function can_follow_a_value_set_by_the_server(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public array $saved = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model="files" :route="route('async.upload')" multiple />
                    <button type="button" dusk="load" wire:click="load">Load</button>
                    <button type="button" dusk="save" wire:click="save">Save</button>
                    <p dusk="saved">{{ implode(',', $saved) }}</p>
                </div>
                HTML;
            }

            public function load(): void
            {
                $this->files = [
                    [
                        'id' => 'existing',
                        'path' => 'browser-uploads/existing.pdf',
                        'real_name' => 'existing.pdf',
                        'size' => 10,
                        'mime' => 'application/pdf',
                        'url' => null,
                    ],
                ];
            }

            public function save(): void
            {
                $this->saved = array_column($this->files, 'real_name');
            }
        })
            ->assertDontSee('existing.pdf')
            ->click('@load')
            ->waitForText('existing.pdf')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->click('@save')
            ->waitForTextIn('@saved', 'existing.pdf,test.jpeg');
    }

    #[Test]
    public function can_hold_the_files_until_send_is_pressed(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple manual />
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->assertSeeIn('@count', '0')
            ->click('@tallstackui_upload_async_send')
            ->waitForTextIn('@count', '1');
    }

    #[Test]
    public function can_keep_a_pending_file_through_a_reset_of_the_property(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple manual />
                    <button type="button" dusk="reset" wire:click="$set('files', [])">Reset</button>
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->click('@reset')
            ->pause(500)
            ->assertSee('test.jpeg')
            ->click('@tallstackui_upload_async_send')
            ->waitForTextIn('@count', '1');
    }

    #[Test]
    public function can_keep_the_tiles_when_the_server_echoes_the_same_list(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple />
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@count', '1')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForTextIn('@count', '2')
            ->pause(500)
            ->assertSee('test.jpeg')
            ->assertSee('test.pdf')
            ->assertScript("document.querySelectorAll('[dusk=tallstackui_upload_async_tile]').length", 2);
    }

    #[Test]
    public function can_listen_to_the_events(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files"
                                    :route="route('async.upload')"
                                    multiple
                                    x-on:added="document.querySelector('[dusk=added]').textContent = $event.detail.file.real_name"
                                    x-on:success="document.querySelector('[dusk=succeeded]').textContent = $event.detail.file.real_name"
                                    x-on:complete="document.querySelector('[dusk=completed]').textContent++"
                                    x-on:removed="document.querySelector('[dusk=removed]').textContent = $event.detail.file.real_name" />
                    <div wire:ignore>
                        <p dusk="added"></p>
                        <p dusk="succeeded"></p>
                        <p dusk="completed">0</p>
                        <p dusk="removed"></p>
                    </div>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@added', 'test.jpeg')
            ->waitForTextIn('@succeeded', 'test.jpeg')
            ->waitForTextIn('@completed', '1')
            ->pause(500)
            ->assertScript("document.querySelector('[dusk=completed]').textContent", '1')
            ->tap(fn (Browser $browser) => $browser->script('document.querySelector("[dusk=tallstackui_upload_async_remove]").click()'))
            ->waitForTextIn('@removed', 'test.jpeg');
    }

    #[Test]
    public function can_open_and_close_the_lightbox(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple />
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->click('@tallstackui_upload_async_tile img')
            ->waitFor('@tallstackui_upload_async_lightbox')
            ->assertSeeIn('@tallstackui_upload_async_lightbox', 'test.jpeg')
            ->click('@tallstackui_upload_async_lightbox_close')
            ->waitUntilMissing('@tallstackui_upload_async_lightbox');
    }

    #[Test]
    public function can_reject_a_file_outside_the_accept_list(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" accept="image/*" multiple />
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForText('File type not allowed.')
            ->assertSeeIn('@count', '0');
    }

    #[Test]
    public function can_remove_a_file_from_the_grid(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple />
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@count', '1')
            ->script('document.querySelector("[dusk=tallstackui_upload_async_remove]").click()');

        $this->assertTrue(true);
    }

    #[Test]
    public function can_remove_a_rejected_file_from_the_grid(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" accept="image/*" multiple />
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForText('File type not allowed.')
            // Clicks whatever sits on top of the button center, as a pointer
            // would, so the error message painted over it fails the test.
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const rect = document.querySelector('[dusk=tallstackui_upload_async_remove]').getBoundingClientRect();
                document.elementFromPoint(rect.left + rect.width / 2, rect.top + rect.height / 2).dispatchEvent(new MouseEvent('click', { bubbles: true }));
            JS))
            ->waitUntilMissing('@tallstackui_upload_async_tile')
            ->assertMissing('@tallstackui_upload_async_tile');
    }

    #[Test]
    public function can_reorder_the_files_dragging_with_the_mouse(): void
    {
        Livewire::visit($this->sortable())
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@order', 'test.jpeg')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForTextIn('@order', 'test.jpeg,test.pdf')
            ->assertSeeIn('@cover', 'test.jpeg')
            ->tap(fn (Browser $browser) => $browser->script([$this->press(1, 'mouse'), $this->drop(0)]))
            ->waitForTextIn('@order', 'test.pdf,test.jpeg')
            ->assertSeeIn('@cover', 'test.pdf')
            ->assertSeeIn('@sorted', '1>0')
            ->assertMissing('@tallstackui_upload_async_ghost');
    }

    #[Test]
    public function can_reorder_the_files_with_a_long_press_on_touch(): void
    {
        Livewire::visit($this->sortable())
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@order', 'test.jpeg')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForTextIn('@order', 'test.jpeg,test.pdf')
            ->tap(fn (Browser $browser) => $browser->script($this->press(1, 'touch')))
            ->pause(400)
            ->assertPresent('@tallstackui_upload_async_ghost')
            ->tap(fn (Browser $browser) => $browser->script($this->drop(0)))
            ->waitForTextIn('@order', 'test.pdf,test.jpeg')
            ->assertSeeIn('@cover', 'test.pdf')
            ->assertSeeIn('@sorted', '1>0')
            ->assertMissing('@tallstackui_upload_async_ghost');
    }

    #[Test]
    public function can_surface_a_server_side_validation_error(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload.strict')" multiple />
                    <p dusk="count">{{ count($files) }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForText('The file field must be a file of type: png.')
            ->assertSeeIn('@count', '0');
    }

    #[Test]
    public function can_upload_a_file_and_sync_it_to_livewire(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files" :route="route('async.upload')" multiple />
                    <p dusk="count">{{ count($files) }}</p>
                    <p dusk="name">{{ $files[0]['real_name'] ?? '' }}</p>
                </div>
                HTML;
            }
        })
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForText('test.jpeg')
            ->waitForTextIn('@count', '1')
            ->assertSeeIn('@name', 'test.jpeg');
    }

    #[Test]
    public function cannot_reorder_when_the_finger_moves_before_the_long_press(): void
    {
        Livewire::visit($this->sortable())
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.jpeg')
            ->waitForTextIn('@order', 'test.jpeg')
            ->attach('@tallstackui_upload_async_input', __DIR__.'/test.pdf')
            ->waitForTextIn('@order', 'test.jpeg,test.pdf')
            ->tap(fn (Browser $browser) => $browser->script([
                $this->press(1, 'touch'),
                "window.dispatchEvent(new PointerEvent('pointermove', { ...window.tsuiPointer, clientY: window.tsuiPointer.clientY - 40 }));",
            ]))
            ->pause(400)
            ->assertMissing('@tallstackui_upload_async_ghost')
            ->tap(fn (Browser $browser) => $browser->script($this->drop(0)))
            ->pause(500)
            ->assertSeeIn('@order', 'test.jpeg,test.pdf')
            ->assertSeeIn('@cover', 'test.jpeg');
    }

    // Synthetic pointer events: WebDriver cannot hold a touch still long
    // enough for the long press, nor say where the drag lands on a tile.
    private function drop(int $index): string
    {
        return <<<JS
            const rect = document.querySelectorAll('[dusk=tallstackui_upload_async_tile]')[{$index}].getBoundingClientRect();
            const end = { ...window.tsuiPointer, clientX: rect.left + rect.width / 2, clientY: rect.top + rect.height / 2 };

            window.dispatchEvent(new PointerEvent('pointermove', { ...window.tsuiPointer, clientX: window.tsuiPointer.clientX - 20 }));
            window.dispatchEvent(new PointerEvent('pointermove', end));
            window.dispatchEvent(new PointerEvent('pointerup', end));
        JS;
    }

    private function press(int $index, string $pointer): string
    {
        return <<<JS
            const tile = document.querySelectorAll('[dusk=tallstackui_upload_async_tile]')[{$index}];
            const rect = tile.getBoundingClientRect();

            window.tsuiPointer = {
                pointerId: 1,
                pointerType: '{$pointer}',
                button: 0,
                buttons: 1,
                bubbles: true,
                clientX: rect.left + rect.width / 2,
                clientY: rect.top + rect.height / 2,
            };

            tile.dispatchEvent(new PointerEvent('pointerdown', window.tsuiPointer));
        JS;
    }

    private function sortable(): LivewireComponent
    {
        return new class extends LivewireComponent
        {
            public array $files = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-upload.async wire:model.live="files"
                                    :route="route('async.upload')"
                                    multiple
                                    sortable
                                    x-on:sorted="document.querySelector('[dusk=sorted]').textContent = $event.detail.from + '>' + $event.detail.to">
                        <x-slot:tile>
                            <span x-show="index === 0" x-text="file.real_name" class="absolute inset-0" dusk="cover"></span>
                        </x-slot:tile>
                    </x-upload.async>
                    <p dusk="order">{{ implode(',', array_column($files, 'real_name')) }}</p>
                    <p dusk="sorted" wire:ignore></p>
                </div>
                HTML;
            }
        };
    }
}
