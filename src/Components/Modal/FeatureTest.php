<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-modal title="Foo Bar" footer="Foo bar baz">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Bar Baz')
        ->toContain('Foo bar baz');
});

it('can thrown exception when wire is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [wire] property cannot be an empty string');

    $component = <<<'HTML'
    <x-modal wire="">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Bar Baz')
        ->toContain('Foo bar baz');
});

it('can thrown exception when size is unnaceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [size] must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full]');

    $component = <<<'HTML'
    <x-modal size="{{ size }}">
    Bar Baz
    </x-modal>
    HTML;

    $component = str_replace('{{ size }}', $size, $component);

    expect($component)->render()
        ->toContain('Bar Baz');
})->with([
    'foo',
    'bar',
    '8xl',
    '9xl',
    '10xl',
]);

it('clips overflow on the scrollable wrapper so the scrollbar respects rounded corners', function () {
    $component = <<<'HTML'
    <x-modal scrollable>
    Long content
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('max-h-[80vh] flex flex-col overflow-hidden');
});

it('does not apply overflow-hidden when not scrollable', function () {
    $component = <<<'HTML'
    <x-modal>
    Short content
    </x-modal>
    HTML;

    expect($component)->render()
        ->not->toContain('max-h-[80vh] flex flex-col overflow-hidden');
});

it('can render centered modal with items-center on all viewports', function () {
    $component = <<<'HTML'
    <x-modal title="Centered" center>
    Content
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('items-center')
        ->toContain('p-4')
        ->toContain('rounded-xl')
        ->not->toContain('items-end');
});

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [z-index] must start with z- prefix');

    $component = <<<'HTML'
    <x-modal z-index="50">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Bar Baz');
});

it('can render without body padding', function () {
    $component = <<<'HTML'
    <x-modal paddingless>
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('p-0!');
});

it('can render with body padding by default', function () {
    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->not->toContain('p-0!');
});

it('can align the footer slot', function (string $attribute, string $class) {
    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer {{ attribute }}>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect(str_replace('{{ attribute }}', $attribute, $component))->render()
        ->toContain('flex gap-2 '.$class);
})->with([
    ['', 'justify-end'],
    ['start', 'justify-start'],
    ['center', 'justify-center'],
    ['end', 'justify-end'],
    ['between', 'justify-between'],
]);

it('can align the footer attribute to the end by default', function () {
    $component = <<<'HTML'
    <x-modal footer="Foo">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('flex gap-2 justify-end');
});

it('can render the footer slot without the aligning wrapper', function () {
    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer unwrapped>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Foo')
        ->toContain('border-t border-t-gray-100')
        ->not->toContain('flex gap-2');
});

it('can merge the footer slot attributes without leaking the alignment keywords', function () {
    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer between class="foo-bar-baz-bah">
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('foo-bar-baz-bah')
        ->toContain('justify-between')
        ->not->toContain('between="between"');
});

it('can keep the footer sticky on the chrome wrapper when scrollable', function () {
    $component = <<<'HTML'
    <x-modal scrollable>
    Bar Baz
    <x-slot:footer>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('border-t border-t-gray-100 p-4 text-gray-700 sticky bottom-0 z-10 bg-white dark:bg-dark-700')
        ->toContain('flex gap-2 justify-end');
});

it('cannot make the footer sticky when not scrollable', function () {
    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render()
        ->not->toContain('sticky bottom-0 z-10');
});

it('can keep the footer sticky when scrollable and unwrapped', function () {
    $component = <<<'HTML'
    <x-modal scrollable>
    Bar Baz
    <x-slot:footer unwrapped>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('sticky bottom-0 z-10 bg-white dark:bg-dark-700')
        ->not->toContain('flex gap-2');
});

it('can thrown exception when the footer slot combines alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [footer] slot cannot combine the alignments [start, end]');

    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer start end>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render();
});

it('can thrown exception when the footer slot mixes unwrapped with alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [footer] slot cannot use [unwrapped] together with [between]');

    $component = <<<'HTML'
    <x-modal>
    Bar Baz
    <x-slot:footer unwrapped between>
        Foo
    </x-slot:footer>
    </x-modal>
    HTML;

    expect($component)->render();
});
