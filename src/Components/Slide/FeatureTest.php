<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar" footer="Foo bar baz">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo bar', 'Bar Baz', 'Foo bar baz');
});

it('can render footer attributes when is not string', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer class="foo-bar-baz-bah">
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo Bar', 'Bar Baz', 'Footer', 'foo-bar-baz-bah');
});

it('cannot leak the alignment keywords into the footer markup', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer between>
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render()
        ->not->toContain('between="between"');
});

it('can align the footer slot', function (string $attribute, string $class) {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer {{ attribute }}>
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect(str_replace('{{ attribute }}', $attribute, $slide))->render()
        ->toContain('flex gap-2 '.$class);
})->with([
    ['', 'justify-end'],
    ['start', 'justify-start'],
    ['center', 'justify-center'],
    ['end', 'justify-end'],
    ['between', 'justify-between'],
]);

it('can align the footer attribute to the end by default', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar" footer="Footer">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('flex gap-2 justify-end');
});

it('can render the footer slot without the aligning wrapper', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer unwrapped>
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Footer')
        ->toContain('border-t border-t-gray-200 px-4 pt-4')
        ->not->toContain('flex gap-2');
});

it('can thrown exception when the footer slot combines alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Slide: The [footer] slot cannot combine the alignments [start, center]');

    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer start center>
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render();
});

it('can thrown exception when the footer slot mixes unwrapped with alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Slide: The [footer] slot cannot use [unwrapped] together with [end]');

    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer unwrapped end>
            Footer
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render();
});

it('can thrown exception when wire is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Slide: The [wire] property cannot be an empty string');

    $slide = <<<'HTML'
    <x-slide wire="">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo bar', 'Bar Baz', 'Foo bar baz');
});

it('can thrown exception when size is unnaceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Slide: The [size] must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full]');

    $slide = <<<HTML
    <x-slide size="$size">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Bar Baz');
})->with([
    'foo',
    'bar',
    '8xl',
    '9xl',
    '10xl',
]);

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);

    $slide = <<<'HTML'
    <x-slide z-index="50">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Bar Baz');
});

it('can render without body padding', function () {
    $slide = <<<'HTML'
    <x-slide paddingless>
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('p-0!');
});

it('can render with body padding by default', function () {
    $slide = <<<'HTML'
    <x-slide>
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->not->toContain('p-0!');
});
