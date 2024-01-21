<?php

namespace Tests\Browser\Form;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithFileUploads;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class UploadTest extends BrowserTestCase
{
    /** @test */
    public function can_delete_file()
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photo)
                        <p dusk="uploaded">{{ $photo->getClientOriginalName() }}</p>
                    @endif
                    
                    <x-upload label="Document" wire:model.live="photo" delete />
                </div>
                HTML;
            }

            public function deleteUpload($originalName, $temporaryName): void
            {
                if (! $this->photo) {
                    return;
                }

                $files = Arr::wrap($this->photo);

                /** @var UploadedFile $file */
                $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $temporaryName)->first();

                rescue(fn () => $file->delete(), report: false);

                $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $temporaryName);

                $this->photo = is_array($this->photo) ? $collect->toArray() : $collect->first();
            }
        });

        $browser->assertSee('Document')
            ->assertMissing('@uploaded')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.jpeg')
            ->waitForTextIn('@uploaded', 'test.jpeg')
            ->assertSeeIn('@uploaded', 'test.jpeg')
            ->waitForLivewire()->clickAtXPath('/html/body/div[3]/div/div[3]/div/div[4]/ul/li/div[2]/button')
            ->assertMissing('@uploaded');
    }

    /** @test */
    public function can_delete_file_using_custom_method()
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photo)
                        <p dusk="uploaded">{{ $photo->getClientOriginalName() }}</p>
                    @endif
                    
                    <x-upload label="Document" wire:model.live="photo" delete delete-method="fooBar" />
                </div>
                HTML;
            }

            public function fooBar($originalName, $temporaryName): void
            {
                if (! $this->photo) {
                    return;
                }

                $files = Arr::wrap($this->photo);

                /** @var UploadedFile $file */
                $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $temporaryName)->first();

                rescue(fn () => $file->delete(), report: false);

                $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $temporaryName);

                $this->photo = is_array($this->photo) ? $collect->toArray() : $collect->first();
            }
        });

        $browser->assertSee('Document')
            ->assertMissing('@uploaded')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.jpeg')
            ->waitForTextIn('@uploaded', 'test.jpeg')
            ->assertSeeIn('@uploaded', 'test.jpeg')
            ->waitForLivewire()->clickAtXPath('/html/body/div[3]/div/div[3]/div/div[4]/ul/li/div[2]/button')
            ->assertMissing('@uploaded');
    }

    /** @test */
    public function can_see_footer_slot()
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>                    
                    <x-upload label="Document" wire:model="photo">
                        <x-slot:footer>
                            Foo Bar Baz
                        </x-slot:footer>
                    </x-upload>
                </div>
                HTML;
            }
        });

        $browser->assertSee('Document')
            ->assertDontSee('Foo Bar Baz')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->waitForText('Foo Bar Baz')
            ->assertSee('Click here to upload')
            ->assertSee('Foo Bar Baz');
    }

    /** @test */
    public function can_see_preview(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photo)
                        <p dusk="uploaded">{{ $photo->getClientOriginalName() }}</p>
                    @endif
                    
                    <x-upload label="Document" wire:model.live="photo" />
                </div>
                HTML;
            }
        });

        $browser->assertSee('Document')
            ->assertMissing('@uploaded')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.jpeg')
            ->waitForTextIn('@uploaded', 'test.jpeg')
            ->assertSeeIn('@uploaded', 'test.jpeg')
            ->click('@tallstackui_file_preview')
            ->waitFor('@tallstackui_file_preview_backdrop')
            ->assertVisible('@tallstackui_file_preview_backdrop');
    }

    /** @test */
    public function can_see_tip()
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>                    
                    <x-upload label="Document" tip="Accept pdf or png" wire:model="photo" />
                </div>
                HTML;
            }
        });

        $browser->assertSee('Document')
            ->assertDontSee('Accept pdf or png')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->waitForText('Accept pdf or png')
            ->assertSee('Click here to upload')
            ->assertSee('Accept pdf or png');
    }

    /** @test */
    public function can_thrown_exception_if_delete_method_does_not_exists()
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photo)
                        <p dusk="uploaded">{{ $photo->getClientOriginalName() }}</p>
                    @endif
                    
                    <x-upload label="Document" wire:model.live="photo" delete />
                </div>
                HTML;
            }
        });

        $browser->assertSee('The [upload] component delete method');
    }

    /** @test */
    public function can_upload_multiple_file(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photos = [];

            protected $saved = [];

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photos && count($photos) === 2)
                        <p dusk="upload-finish">Uploaded</p>
                        @if ($photos && $photos[0])
                            <p dusk="uploaded-0">{{ $photos[0]->getClientOriginalName() }}</p>
                        @endif
                        @if ($photos && $photos[1])
                            <p dusk="uploaded-1">{{ $photos[1]->getClientOriginalName() }}</p>
                        @endif
                    @endif
                    
                    <x-upload label="Document" wire:model="photos" multiple />
                </div>
                HTML;
            }

            public function updatingPhotos(): void
            {
                if (! is_array($this->photos)) {
                    return;
                }

                $this->saved = $this->photos;
            }

            public function updatedPhotos(): void
            {
                if (! $this->photos || ! is_array($this->photos)) {
                    return;
                }

                $file = Arr::flatten(array_merge($this->saved, [$this->photos]));

                $this->photos = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();

                // This is only necessary in tests, to make sure
                // the order of the files is always the same
                $this->photos = array_values($this->photos);
            }

            public function sync()
            {
                // ...
            }
        });

        $browser->assertSee('Document')
            ->assertMissing('@uploaded-0')
            ->assertMissing('@uploaded-1')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.jpeg')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.pdf')
            // This is necessary because Livewire always send
            // request to the backend when file is uploaded
            ->waitForTextIn('@upload-finish', 'Uploaded')
            ->waitForTextIn('@uploaded-0', 'test.jpeg')
            ->assertSeeIn('@uploaded-0', 'test.jpeg')
            ->waitForTextIn('@uploaded-1', 'test.pdf')
            ->assertSeeIn('@uploaded-1', 'test.pdf');
    }

    /** @test */
    public function can_upload_single_file(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            use WithFileUploads;

            public $photo;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    @if ($photo)
                        <p dusk="uploaded">{{ $photo->getClientOriginalName() }}</p>
                    @endif
                    
                    <x-upload label="Document" wire:model.live="photo" />
                </div>
                HTML;
            }
        });

        $browser->assertSee('Document')
            ->assertMissing('@uploaded')
            ->click('@tallstackui_upload_input')
            ->waitForText('Click here to upload')
            ->attach('@tallstackui_file_select', __DIR__.'/../../Fixtures/test.jpeg')
            ->waitForTextIn('@uploaded', 'test.jpeg')
            ->assertSeeIn('@uploaded', 'test.jpeg');
    }
}
