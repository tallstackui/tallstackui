<?php

use Illuminate\Support\Facades\File;
use Illuminate\View\ViewException;
use TallStackUi\Components\Editor\Component;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('cannot render without a binding', function () {
    $this->expectException(ViewException::class);

    expect('<x-editor />')->render();
});

it('can render the editable', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('tallstackui_editor(')
        ->toContain('role="textbox"')
        ->toContain('aria-multiline="true"')
        ->toContain('contenteditable="true"');
});

it('can render the whole default toolbar', function () {
    $html = (string) expect('<x-editor name="content" />')->render()->value;

    foreach ([
        'style', 'blockquote', 'bold', 'italic', 'underline', 'strikethrough',
        'ordered_list', 'unordered_list', 'indent', 'outdent', 'align',
        'code', 'code_block', 'clear_format', 'link', 'image', 'hr',
        'undo', 'redo', 'fullscreen',
    ] as $slug) {
        expect($html)->toContain('dusk="tallstackui_editor_'.$slug.'"');
    }

    expect($html)->toContain('role="toolbar"');
});

it('can render only the whitelisted buttons', function () {
    expect('<x-editor name="content" :toolbar="[\'bold\', \'italic\']" />')
        ->render()
        ->toContain('dusk="tallstackui_editor_bold"')
        ->toContain('dusk="tallstackui_editor_italic"')
        ->not->toContain('dusk="tallstackui_editor_undo"')
        ->not->toContain('dusk="tallstackui_editor_fullscreen"');
});

it('cannot render an unknown toolbar button', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/unknown button\(s\): \[banana\]/');

    expect('<x-editor name="content" :toolbar="[\'bold\', \'banana\']" />')->render();
});

it('cannot render an empty toolbar', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/\[toolbar\] cannot be empty/');

    expect('<x-editor name="content" :toolbar="[]" />')->render();
});

it('can render a divider only when the group changes', function () {
    // bold and italic share the inline group, ordered-list opens a new one.
    expect('<x-editor name="content" :toolbar="[\'bold\', \'italic\']" />')
        ->render()
        ->not->toContain('data-tsui-editor-divider');

    expect('<x-editor name="content" :toolbar="[\'bold\', \'ordered-list\']" />')
        ->render()
        ->toContain('data-tsui-editor-divider');
});

it('can render readonly without the toolbar', function () {
    expect('<x-editor name="content" readonly />')
        ->render()
        ->not->toContain('role="toolbar"')
        ->toContain('contenteditable="false"')
        ->toContain('aria-disabled="true"');
});

it('can render disabled without the toolbar', function () {
    expect('<x-editor name="content" disabled />')
        ->render()
        ->not->toContain('role="toolbar"')
        ->toContain('contenteditable="false"')
        ->toContain('aria-disabled="true"');
});

it('cannot render readonly and disabled together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/\[readonly\] and \[disabled\] cannot be used together/');

    expect('<x-editor name="content" readonly disabled />')->render();
});

it('can render the counters by default', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('aria-live="polite"')
        ->toContain('formatCount(words,')
        ->toContain('formatCount(lines,');
});

it('can render without the counters', function () {
    expect('<x-editor name="content" :counters="false" />')
        ->render()
        ->not->toContain('formatCount(words,');
});

it('can render the heights coming from the configuration', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('min-height: 12rem; max-height: 40rem;');
});

it('can render the heights coming from the attributes', function () {
    expect('<x-editor name="content" min-height="200px" max-height="90vh" />')
        ->render()
        ->toContain('min-height: 200px; max-height: 90vh;');
});

it('can render the label and the hint', function () {
    expect('<x-editor name="content" label="Body" hint="Keep it short" />')
        ->render()
        ->toContain('Body')
        ->toContain('Keep it short');
});

it('can render the placeholder coming from the translations', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('data-placeholder="'.trans('ts-ui::messages.editor.placeholder').'"');
});

it('can render a custom placeholder', function () {
    expect('<x-editor name="content" placeholder="Write the post" />')
        ->render()
        ->toContain('data-placeholder="Write the post"');
});

it('can render the required and invalid attributes', function () {
    expect('<x-editor name="content" required />')
        ->render()
        ->toContain('aria-required="true"');
});

it('can render the hidden input when using the name attribute', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('<input type="hidden" name="content"');
});

it('can render an initial value', function () {
    expect('<x-editor name="content" value="<p>Foo</p>" />')
        ->render()
        ->toContain('\u003Cp\u003EFoo\u003C\/p\u003E');
});

it('cannot render the upload area without the upload attributes', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->not->toContain('dusk="tallstackui_editor_image_upload"');
});

it('cannot render with only one of the upload attributes', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/must be used together/');

    expect('<x-editor name="content" upload-property="image" />')->render();
});

it('cannot render the upload outside of livewire', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/requires the component to be used within a Livewire context/');

    expect('<x-editor name="content" upload-property="image" upload-method="store" />')->render();
});

it('can render the sanitization whitelist', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('allowed_tags')
        ->toContain('allowed_attributes')
        ->toContain('allowed_styles');
});

it('can render the style dropdown narrow', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('data-tsui-dropdown-width="xs"');
});

it('can render the toolbar activatable by keyboard', function () {
    // The actions live on click and keydown rather than mousedown alone, so
    // Enter and Space reach them through the roving tabindex.
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('x-on:click="exec(\'bold\')"')
        ->toContain('x-on:keydown.enter.prevent="toggleBlock(\'h1\'); show = false"');
});

it('can render the link dialog guarding the url scheme', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('validLinkUrl');
});

it('can render the toolbar dropdown', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('dusk="tallstackui_editor_style"')
        ->toContain('blockLabel()')
        ->toContain("toggleBlock('h1')")
        ->toContain("toggleBlock('h3')")
        ->not->toContain("toggleBlock('h4')");
});

it('cannot render the removed size button', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/unknown button\(s\): \[size\]/');

    expect('<x-editor name="content" :toolbar="[\'size\']" />')->render();
});

it('can render both dialogs as scoped modals', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('modal:content-link-open')
        ->toContain('modal:content-image-open')
        ->toContain('x-teleport="body"')
        ->toContain('insertLink()')
        ->toContain('insertImage()')
        ->toContain('dialogClosed()');
});

it('can render applying a soft customization', function () {
    TallStackUi::customize('editor')->block('toolbar.wrapper', 'foo-bar-baz');

    expect('<x-editor name="content" />')
        ->render()
        ->toContain('foo-bar-baz');
});

it('can render applying a scoped customization', function () {
    TallStackUi::customize('editor', scope: 'compact')->block('toolbar.wrapper', 'scoped-toolbar');

    expect('<x-editor name="content" scope="compact" />')
        ->render()
        ->toContain('scoped-toolbar');

    expect('<x-editor name="content" />')
        ->render()
        ->not->toContain('scoped-toolbar');
});

it('can render storing html by default', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('markdown: false');
});

it('can render storing markdown through the attribute', function () {
    expect('<x-editor name="content" markdown />')
        ->render()
        ->toContain('markdown: true');
});

it('can render storing markdown through the configuration', function () {
    config()->set('ts-ui.components.editor.1.markdown', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-editor name="content" />')
        ->render()
        ->toContain('markdown: true');
})->after(function () {
    // The resolved configuration is cached in a static, so it outlives the
    // application the test case rebuilds.
    config()->set('ts-ui.components.editor.1.markdown', false);
    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render dropping the buttons markdown cannot express', function () {
    expect('<x-editor name="content" markdown />')
        ->render()
        ->not->toContain('dusk="tallstackui_editor_underline"')
        ->not->toContain('dusk="tallstackui_editor_align"')
        ->toContain('dusk="tallstackui_editor_bold"')
        ->toContain('dusk="tallstackui_editor_blockquote"');
});

it('can render dropping the incompatible buttons written by hand', function () {
    expect('<x-editor name="content" markdown :toolbar="[\'bold\', \'underline\', \'align\']" />')
        ->render()
        ->toContain('dusk="tallstackui_editor_bold"')
        ->not->toContain('dusk="tallstackui_editor_underline"')
        ->not->toContain('dusk="tallstackui_editor_align"');
});

it('can render a toolbar left empty by the markdown filter', function () {
    // The empty check answers for the attribute as it was written: a toolbar
    // emptied by the filter renders empty rather than throwing.
    expect('<x-editor name="content" markdown :toolbar="[\'underline\']" />')
        ->render()
        ->toContain('role="toolbar"')
        ->not->toContain('dusk="tallstackui_editor_underline"');
});

it('cannot render an unknown toolbar button while storing markdown', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessageMatches('/unknown button\(s\): \[banana\]/');

    expect('<x-editor name="content" markdown :toolbar="[\'bold\', \'banana\']" />')->render();
});

it('can render the blockquote and the horizontal rule', function () {
    expect('<x-editor name="content" :toolbar="[\'blockquote\', \'hr\']" />')
        ->render()
        ->toContain('toggleBlockquote()')
        ->toContain('insertRule()')
        ->toContain('activeFormats.blockquote');
});

it('can render the blockquote and the horizontal rule in the whitelist', function () {
    expect('<x-editor name="content" />')
        ->render()
        ->toContain('blockquote')
        ->toContain('hr');
});

it('has the editor translations in every shipped locale', function () {
    $locales = collect(File::directories(__DIR__.'/../../../lang'))->map(fn (string $path) => basename($path));

    expect($locales)->not->toBeEmpty();

    foreach ($locales as $locale) {
        $messages = require __DIR__."/../../../lang/{$locale}/messages.php";

        foreach ([
            'editor.placeholder',
            'editor.tooltip.bold',
            'editor.tooltip.blockquote',
            'editor.tooltip.hr',
            'editor.tooltip.fullscreen',
            'editor.style.h1',
            'editor.style.h3',
            'editor.align.justify',
            'editor.image.errors.size',
            'editor.link.title',
            'editor.counters.words',
        ] as $key) {
            expect(data_get($messages, $key))->not->toBeNull("The locale [{$locale}] is missing {$key}");
        }

        expect($messages['editor']['counters']['words'])->toContain('|');
        expect($messages['editor']['image']['errors']['size'])->toContain(':max');
    }
});

it('has every toolbar icon registered in the icon guide', function () {
    $guide = require __DIR__.'/../../config.php';

    $registered = array_keys($guide['components']['icon'][1]['custom']['guide']);

    foreach ([
        'numbered-list', 'list-bullet', 'chevron-double-right', 'chevron-double-left',
        'bars-3-bottom-left', 'code-bracket', 'code-bracket-square', 'backspace',
        'link', 'photo', 'arrow-uturn-left', 'arrow-uturn-right', 'eye', 'minus',
        'arrows-pointing-out', 'arrows-pointing-in', 'arrow-up-tray', 'x-mark',
    ] as $icon) {
        expect($registered)->toContain($icon);
        expect(File::exists(__DIR__."/../../resources/views/components/icon/heroicons/outline/{$icon}.blade.php"))
            ->toBeTrue("The icon [{$icon}] does not exist");
    }
});

it('cannot leak an uncompiled blade directive into the markup', function () {
    // A directive placed inside a component attribute is never compiled: it
    // reaches the browser verbatim and Alpine chokes on it.
    expect('<x-editor name="content" />')
        ->render()
        ->not->toContain('@js(')
        ->not->toContain('@class(')
        ->not->toContain('{{ $');
});
