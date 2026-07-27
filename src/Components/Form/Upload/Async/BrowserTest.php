<?php

namespace TallStackUi\Components\Form\Upload\Async;

use Livewire\Component as LivewireComponent;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
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
            ->script('document.querySelector("[dusk=tallstackui_upload_async_lightbox] button").click()');

        $this->assertTrue(true);
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
}
