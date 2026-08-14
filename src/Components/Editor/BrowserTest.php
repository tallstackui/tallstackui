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
            ->waitUntil($this->booted())
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
    public function can_apply_a_heading_inside_a_list_item(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<ul><li>foo</li></ul>';

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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->caretInsideItem()))
            ->click('@tallstackui_editor_style')
            ->pause(400)
            ->clickAtXPath('//button[contains(., "Heading 3")]')
            ->pause(700)
            ->assertSeeIn('@output', '<h3>foo</h3>');
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_bold')
            ->pause(700)
            ->assertSeeIn('@output', '<strong>foo</strong>');
    }

    #[Test]
    public function can_apply_bold_via_the_keyboard(): void
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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->keys('@tallstackui_editor_bold', '{enter}')
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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->keys('@tallstackui_editor_editable', ['{control}', 'b'])
            ->pause(700)
            ->assertSeeIn('@output', '<strong>foo</strong>');
    }

    #[Test]
    public function can_autoformat_a_heading(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', '## Title')
            ->pause(900)
            ->assertSeeIn('@output', '## Title');
    }

    #[Test]
    public function can_autoformat_bold(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', '**loud**')
            ->pause(900)
            ->assertSeeIn('@output', '**loud**');
    }

    #[Test]
    public function can_boot_from_markdown(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = "# Title\n\nSome **bold** text.\n\n- one\n- two";

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->pause(700)
            ->assertPresent('[dusk=tallstackui_editor_editable] h1')
            ->assertPresent('[dusk=tallstackui_editor_editable] strong')
            ->assertPresent('[dusk=tallstackui_editor_editable] ul li');
    }

    #[Test]
    public function can_change_a_heading_inside_a_list_item(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<ul><li><h3>foo</h3></li></ul>';

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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->caretInsideItem()))
            ->click('@tallstackui_editor_style')
            ->pause(400)
            ->clickAtXPath('//button[contains(., "Heading 1")]')
            ->pause(700)
            ->assertSeeIn('@output', '<h1>foo</h1>')
            ->assertDontSeeIn('@output', '<h3>');
    }

    #[Test]
    public function can_count_a_trailing_empty_line_once(): void
    {
        // innerText writes a break for the block and one for its filler <br>,
        // which used to read a single trailing empty line as two.
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
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'teste', '{enter}')
            ->pause(700)
            ->assertSee('1 word')
            ->assertSee('2 lines');
    }

    #[Test]
    public function can_count_paragraphs_as_single_lines(): void
    {
        // Paragraphs separate with two breaks in innerText, which used to
        // count a third line between these two.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p><p>bar</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->waitUntil("!! document.querySelector('[dusk=tallstackui_editor_editable]')")
            ->pause(700)
            ->assertSee('2 words')
            ->assertSee('2 lines');
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
    public function can_insert_a_blockquote(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = 'foo';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_blockquote')
            ->pause(700)
            ->assertSeeIn('@output', '> foo');
    }

    #[Test]
    public function can_insert_a_horizontal_rule(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = 'foo';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_hr')
            ->pause(700)
            ->assertSeeIn('@output', '---');
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
    public function can_insert_an_image_with_the_enter_key(): void
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
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_image')
            ->pause(600)
            ->type('#content-image-url', 'https://tallstackui.com/logo.png')
            ->pause(300)
            ->keys('#content-image-url', '{enter}')
            ->pause(800)
            ->assertSeeIn('@output', '<img src="https://tallstackui.com/logo.png"');
    }

    #[Test]
    public function can_keep_the_caret_line_while_empty(): void
    {
        // An empty editable draws a minimum-height caret beside the
        // placeholder: the seeded filler gives it a line box, and reads as an
        // empty document on the way out.
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
            ->waitUntil($this->booted())
            ->waitUntil("!! document.querySelector('[dusk=tallstackui_editor_editable]')")
            ->pause(700)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_editable]').innerHTML",
                '<br>'
            )
            ->assertScript("document.querySelector('[dusk=output]').innerText", '')
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'abc')
            ->pause(700)
            ->assertSeeIn('@output', 'abc');
    }

    #[Test]
    public function can_open_a_dialog_without_a_scroll_jump_on_a_short_viewport(): void
    {
        // The dialogs focus no field on open. This guards the enter
        // transition against a premature focus ever being reintroduced:
        // focusing a field while the panel is still translated below the
        // viewport makes the browser scroll the dialog wrapper to reveal
        // it, jolting the panel.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->resize(574, 700)
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                window.__maxScroll = 0;
                const wrapper = document.querySelector('#content-image .overflow-y-auto');
                const start = performance.now();
                const tick = () => {
                    window.__maxScroll = Math.max(window.__maxScroll, wrapper.scrollTop);
                    if (performance.now() - start < 900) {
                        requestAnimationFrame(tick);
                    }
                };
                requestAnimationFrame(tick);
            JS))
            ->click('@tallstackui_editor_image')
            ->waitFor('#content-image-url')
            ->pause(1000)
            ->assertScript('window.__maxScroll', 0);
    }

    #[Test]
    public function can_paste_markdown_copied_from_a_code_editor(): void
    {
        // VS Code and friends ship the syntax highlighting as a text/html
        // flavour next to the text. Reading that as rich content is what makes
        // pasting a .md file arrive as literal characters.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                editable.focus();
                const transfer = new DataTransfer();
                transfer.setData(
                    'text/html',
                    '<div style="color:#d4d4d4;background:#1e1e1e"><div><span style="color:#569cd6"># Title</span></div>'
                        + '<div><span style="color:#6a9955">- one</span></div></div>'
                );
                transfer.setData('text/plain', '# Title\n\n- one');
                editable.dispatchEvent(
                    new ClipboardEvent('paste', { clipboardData: transfer, bubbles: true, cancelable: true })
                );
            JS))
            ->pause(800)
            ->assertPresent('[dusk=tallstackui_editor_editable] h1')
            ->assertPresent('[dusk=tallstackui_editor_editable] ul li')
            ->assertSeeIn('@output', '# Title')
            ->assertSeeIn('@output', '- one');
    }

    #[Test]
    public function can_paste_plain_text_as_markdown(): void
    {
        // A clipboard holding only text/plain is the one case the rich paste
        // cannot serve: pasting a .md file would otherwise land as literal
        // characters and be escaped straight back out.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                editable.focus();
                const transfer = new DataTransfer();
                transfer.setData('text/plain', '# Title\n\n- one\n- two\n\nSome **bold** text.');
                editable.dispatchEvent(
                    new ClipboardEvent('paste', { clipboardData: transfer, bubbles: true, cancelable: true })
                );
            JS))
            ->pause(800)
            ->assertPresent('[dusk=tallstackui_editor_editable] h1')
            ->assertPresent('[dusk=tallstackui_editor_editable] ul li')
            ->assertPresent('[dusk=tallstackui_editor_editable] strong')
            ->assertSeeIn('@output', '# Title')
            ->assertSeeIn('@output', '- one')
            ->assertSeeIn('@output', '**bold**');
    }

    #[Test]
    public function can_paste_structured_html_while_storing_markdown(): void
    {
        // The other side of the same gate: a payload that does carry structure
        // is still read as rich content, then serialized to markdown.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                editable.focus();
                const transfer = new DataTransfer();
                transfer.setData('text/html', '<h2>From a page</h2><p>with <strong>bold</strong> in it</p>');
                transfer.setData('text/plain', 'From a page\nwith bold in it');
                editable.dispatchEvent(
                    new ClipboardEvent('paste', { clipboardData: transfer, bubbles: true, cancelable: true })
                );
            JS))
            ->pause(800)
            ->assertSeeIn('@output', '## From a page')
            ->assertSeeIn('@output', '**bold**');
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
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', 'Hello world')
            ->pause(700)
            ->assertSeeIn('@output', 'Hello world');
    }

    #[Test]
    public function can_remove_a_heading_inside_a_list_item(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<ul><li><h3>foo</h3></li></ul>';

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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->caretInsideItem()))
            ->click('@tallstackui_editor_style')
            ->pause(400)
            ->clickAtXPath('//button[contains(., "Paragraph")]')
            ->pause(700)
            ->assertDontSeeIn('@output', '<h3>')
            ->assertSeeIn('@output', 'foo');
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
            ->waitUntil($this->booted())
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
    public function can_round_trip_a_link_with_parentheses(): void
    {
        // A parenthesis in the destination closes the markdown () early on the
        // way back in, so the serializer percent-encodes it.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = 'foo';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_link')
            ->pause(600)
            ->type('#content-link-url', 'https://example.com/a(b)')
            ->pause(300)
            ->click('@tallstackui_editor_link_insert')
            ->pause(800)
            ->assertSeeIn('@output', '[foo](https://example.com/a%28b%29)');
    }

    #[Test]
    public function can_sanitize_a_javascript_link_on_boot(): void
    {
        // The attribute whitelist keeps href; the scheme is what carries the
        // script, so it is dropped on its own.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p><a href="javascript:alert(1)">foo</a> bar</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->waitUntil("!! document.querySelector('[dusk=tallstackui_editor_editable]')")
            ->pause(700)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_editable]').innerHTML",
                '<p><a>foo</a> bar</p>'
            );
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
            ->waitUntil("!! document.querySelector('[dusk=tallstackui_editor_editable]')")
            ->pause(700)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_editable]').innerHTML",
                '<p>hi</p>'
            );
    }

    #[Test]
    public function can_store_a_list_as_markdown(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = 'foo';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_unordered_list')
            ->pause(700)
            ->assertSeeIn('@output', '- foo');
    }

    #[Test]
    public function can_store_bold_as_markdown(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = 'foo';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_bold')
            ->pause(700)
            ->assertSeeIn('@output', '**foo**');
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_code_block')
            ->pause(700)
            ->assertSeeIn('@output', '<pre>')
            ->click('@tallstackui_editor_undo')
            ->pause(700)
            ->assertDontSeeIn('@output', '<pre>');
    }

    #[Test]
    public function can_undo_an_autoformat(): void
    {
        // Every transform goes through execCommand precisely so that it lands
        // in the native undo stack: this is the way out for anyone who meant
        // to type the marker.
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" markdown />
                    <p dusk="output" x-text="$wire.content"></p>
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->keys('@tallstackui_editor_editable', '# Title')
            ->pause(900)
            ->assertSeeIn('@output', '# Title')
            ->click('@tallstackui_editor_undo')
            ->pause(900)
            ->assertDontSeeIn('@output', '# Title');
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
            ->waitUntil($this->booted())
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
            ->waitUntil($this->booted())
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
            ->assertScript("document.activeElement.getAttribute('dusk')", 'tallstackui_editor_blockquote');
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
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()));

        foreach (range(1, 10) as $ignored) {
            $browser->click('@tallstackui_editor_indent')->pause(120);
        }

        $browser->pause(500)->assertSeeIn('@output', 'margin-left: 16rem');
    }

    #[Test]
    public function cannot_insert_a_javascript_link(): void
    {
        Livewire::visit(new class extends LivewireComponent
        {
            public string $content = '<p>foo</p>';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-editor wire:model="content" />
                </div>
                HTML;
            }
        })
            ->waitUntil($this->booted())
            ->tap(fn (Browser $browser) => $browser->script($this->selectAll()))
            ->click('@tallstackui_editor_link')
            ->pause(600)
            ->type('#content-link-url', 'javascript:alert(1)')
            ->pause(300)
            ->assertScript(
                "document.querySelector('[dusk=tallstackui_editor_link_insert]').disabled",
                true
            );
    }

    #[Test]
    public function cannot_paste_plain_text_as_markdown_outside_of_markdown_mode(): void
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
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_editable')
            ->tap(fn (Browser $browser) => $browser->script(<<<'JS'
                const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
                editable.focus();
                const transfer = new DataTransfer();
                transfer.setData('text/plain', '# Title');
                editable.dispatchEvent(
                    new ClipboardEvent('paste', { clipboardData: transfer, bubbles: true, cancelable: true })
                );
            JS))
            ->pause(800)
            ->assertMissing('[dusk=tallstackui_editor_editable] h1')
            ->assertSeeIn('@output', '# Title');
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
            ->waitUntil($this->booted())
            ->click('@tallstackui_editor_image')
            ->pause(600)
            ->attach('input[type=file]', __DIR__.'/test.jpeg')
            ->pause(1200)
            ->assertSee('File type not allowed.');
    }

    /**
     * The editable is rendered empty and filled on boot, so anything typed or
     * clicked before that is wiped by the very first render.
     */
    private function booted(): string
    {
        return "!! document.querySelector('[dusk=tallstackui_editor_editable]')?.innerHTML";
    }

    /** Put the caret around the contents of the first list item. */
    private function caretInsideItem(): string
    {
        return <<<'JS'
        const editable = document.querySelector('[dusk=tallstackui_editor_editable]');
        editable.focus();
        const range = document.createRange();
        range.selectNodeContents(editable.querySelector('li'));
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        JS;
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
