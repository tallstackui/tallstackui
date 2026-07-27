<?php

namespace TallStackUi\Components\Editor;

use Laravel\Dusk\Browser;
use Livewire\Component as LivewireComponent;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Livewire\WithFileUploads;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class BrowserTest extends BrowserTestCase
{
    #[Test]
    public function can_align_an_image(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_image')
            ->pause(500)
            ->type('#content-image-url', 'https://tallstackui.com/logo.png')
            ->pause(300)
            ->click('@tallstackui_editor_image_insert')
            ->pause(700)
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_align')
            ->pause(400)
            ->clickAtXPath('//button[contains(., "Center")]')
            ->pause(700)
            ->assertSeeIn('@output', 'text-align: center');
    }

    #[Test]
    public function can_apply_a_heading_via_the_dropdown(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_style')
            ->pause(400)
            ->clickAtXPath('//button[contains(., "Heading 1")]')
            ->pause(700)
            ->assertSeeIn('@output', '<h1>foo</h1>');
    }

    #[Test]
    public function can_apply_bold_via_the_button(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_bold')
            ->pause(700)
            ->assertSeeIn('@output', '<strong>foo</strong>');
    }

    #[Test]
    public function can_apply_bold_via_the_shortcut(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->keys('@tallstackui_editor_editable', ['{control}', 'b'])
            ->pause(700)
            ->assertSeeIn('@output', '<strong>foo</strong>');
    }

    #[Test]
    public function can_count_the_words_and_the_lines(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'one two three')
            ->pause(700)
            ->assertSee('3 words')
            ->assertSee('1 line');
    }

    #[Test]
    public function can_create_an_ordered_list(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_ordered_list')
            ->pause(700)
            ->assertSeeIn('@output', '<ol>');
    }

    #[Test]
    public function can_indent_a_list_item(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'one', '{enter}', 'two')
            ->pause(400)
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_ordered_list')
            ->pause(500)
            ->assertSeeIn('@output', '<ol>')
            // Collapse into the last item, the only one a browser will ever
            // agree to indent: the first has nothing to nest under.
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                const items = editable.querySelectorAll('li');
                const range = document.createRange();
                range.selectNodeContents(items[items.length - 1]);
                range.collapse(false);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                editable.focus();
            JS))
            ->pause(300)
            ->click('@tallstackui_editor_indent')
            ->pause(700)
            ->assertScript(
                "document.querySelectorAll('[dusk=tallstackui_editor_editable] ol ol, [dusk=tallstackui_editor_editable] ol ul').length",
                1
            );
    }

    #[Test]
    public function can_indent_and_outdent_a_plain_paragraph(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_indent')
            ->pause(500)
            ->assertSeeIn('@output', 'margin-left: 2rem')
            ->click('@tallstackui_editor_indent')
            ->pause(500)
            ->assertSeeIn('@output', 'margin-left: 4rem')
            ->click('@tallstackui_editor_outdent')
            ->pause(500)
            ->assertSeeIn('@output', 'margin-left: 2rem')
            ->click('@tallstackui_editor_outdent')
            ->pause(500)
            // Back at zero the declaration is dropped, not left at 0rem.
            ->assertDontSeeIn('@output', 'margin-left');
    }

    #[Test]
    public function can_insert_a_link_via_the_dialog(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_link')
            ->pause(600)
            // The selection rides into the dialog, which is what proves the
            // binding survives the modal being teleported out of the editor.
            ->assertInputValue('#content-link-text', 'foo')
            ->type('#content-link-url', 'https://tallstackui.com')
            ->pause(300)
            ->click('@tallstackui_editor_link_insert')
            ->pause(800)
            ->assertSeeIn('@output', 'href="https://tallstackui.com"');
    }

    #[Test]
    public function can_insert_an_image_via_a_url(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_image')
            ->pause(600)
            ->type('#content-image-url', 'https://tallstackui.com/logo.png')
            ->type('#content-image-alt', 'The logo')
            ->pause(300)
            ->click('@tallstackui_editor_image_insert')
            ->pause(800)
            ->assertSeeIn('@output', '<img src="https://tallstackui.com/logo.png"')
            ->assertSeeIn('@output', 'alt="The logo"');
    }

    #[Test]
    public function can_insert_an_image_via_an_upload(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            use WithFileUploads;

            public string $content = '';

            public ?TemporaryUploadedFile $picture = null;

            public function store(): string
            {
                return '/storage/uploaded.jpeg';
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" upload-property="picture" upload-method="store" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_image')
            ->pause(600)
            ->attach('input[type=file]', __DIR__.'/test.jpeg')
            ->pause(4000)
            ->assertInputValue('#content-image-url', '/storage/uploaded.jpeg')
            ->click('@tallstackui_editor_image_insert')
            ->pause(800)
            ->assertSeeIn('@output', '<img src="/storage/uploaded.jpeg"');
    }

    #[Test]
    public function can_persist_typing_to_the_wire_model(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'Hello world')
            ->pause(700)
            ->assertSeeIn('@output', 'Hello world');
    }

    #[Test]
    public function can_restore_the_selection_when_a_dialog_is_dismissed(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_link')
            ->pause(600)
            ->tap(fn (Browser $browser) => $browser->script($this->escape()))
            ->pause(600)
            // The caret is back where it was, so the toolbar acts on it again.
            ->click('@tallstackui_editor_bold')
            ->pause(700)
            ->assertSeeIn('@output', '<strong>foo</strong>');
    }

    #[Test]
    public function can_sanitize_pasted_html(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_editable')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                editable.focus();
                const transfer = new DataTransfer();
                transfer.setData(
                    'text/html',
                    '<p class="MsoNormal" style="color:red"><strong>hi</strong></p><iframe src="x"></iframe>'
                );
                editable.dispatchEvent(
                    new ClipboardEvent('paste', { clipboardData: transfer, bubbles: true, cancelable: true })
                );
            JS))
            ->pause(800)
            ->assertSeeIn('@output', '<strong>hi</strong>')
            ->assertDontSeeIn('@output', 'MsoNormal')
            ->assertDontSeeIn('@output', 'iframe')
            ->assertDontSeeIn('@output', 'color');
    }

    #[Test]
    public function can_sanitize_the_html_it_boots_with(): void
    {
        // innerHTML never runs a <script>, but it does fire an <img onerror>,
        // and the stored value is the path that reaches every reader.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p onclick="alert(1)">hi</p><iframe src="x"></iframe>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                </div>
                HTML;
            }
        })
            ->waitUntil("!! document.querySelector('[dusk=tallstackui_editor_editable]')")
            ->pause(700)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_editable]').innerHTML",
                '<p>hi</p>'
            );
    }

    #[Test]
    public function can_toggle_fullscreen_and_leave_with_escape(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_fullscreen')
            ->pause(500)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor]').classList.contains('fixed')",
                true
            )
            ->tap(fn (Browser $browser) => $browser->script($this->escape()))
            ->pause(500)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor]').classList.contains('fixed')",
                false
            );
    }

    #[Test]
    public function can_undo_a_code_block(): void
    {
        // The block used to be built straight into the DOM, which the browser's
        // own undo stack never sees: undo left the <pre> sitting there.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_code_block')
            ->pause(700)
            ->assertSeeIn('@output', '<pre>')
            ->click('@tallstackui_editor_undo')
            ->pause(700)
            ->assertDontSeeIn('@output', '<pre>');
    }

    #[Test]
    public function can_undo_and_redo_typing(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'abc')
            ->pause(700)
            ->assertSeeIn('@output', 'abc')
            ->click('@tallstackui_editor_undo')
            ->pause(700)
            ->assertDontSeeIn('@output', 'abc')
            ->click('@tallstackui_editor_redo')
            ->pause(700)
            ->assertSeeIn('@output', 'abc');
    }

    #[Test]
    public function can_walk_the_toolbar_with_the_arrow_keys(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_style]').getAttribute('tabindex')",
                '0'
            )
            // The toolbar is a div, so it never takes the keys itself: the event
            // is raised on the focused control and bubbles up to the handler.
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const first = document.querySelector('[dusk=tallstackui_editor_style]');
                first.focus();
                first.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
            JS))
            ->pause(500)
            ->assertScript("document.activeElement.getAttribute('dusk')", 'tallstackui_editor_bold');
    }

    #[Test]
    public function cannot_indent_a_paragraph_past_the_limit(): void
    {
        $browser = Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()));

        foreach (range(1, 10) as $ignored) {
            $browser->click('@tallstackui_editor_indent')->pause(120);
        }

        $browser->pause(500)->assertSeeIn('@output', 'margin-left: 16rem');
    }

    #[Test]
    public function cannot_upload_an_image_of_a_rejected_type(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            use WithFileUploads;

            public string $content = '';

            public ?TemporaryUploadedFile $picture = null;

            public function store(): string
            {
                return '';
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content"
                              upload-property="picture"
                              upload-method="store"
                              :upload-mimes="['image/png']" />
                </div>
                HTML;
            }
        })
            ->click('@tallstackui_editor_image')
            ->pause(600)
            ->attach('input[type=file]', __DIR__.'/test.jpeg')
            ->pause(1200)
            ->assertSee('File type not allowed.');
    }

    /** Both Escape listeners sit on window, so the event goes straight there. */
    private function escape(): string
    {
        return "window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }))";
    }

    /** Put the caret around everything the editable holds. */
    private function selectAll(): string
    {
        return <<<'JS'
        const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
        editable.focus();
        const range = document.createRange();
        range.selectNodeContents(editable);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        JS;
    }
}
