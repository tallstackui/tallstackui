<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar');

it('can render with title')
    ->expect('<x-card title="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with footer')
    ->expect('<x-card footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with title and footer')
    ->expect('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Lorem Ipsum')
    ->toContain('Bar Baz');

it('can render with image')
    ->expect('<x-card image="https://via.placeholder.com/150">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('https://via.placeholder.com/150');

it('can render with loading')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('wire:loading')
    ->toContain('wire:target="save"');

it('can render with loading and delay')
    ->expect('<x-card loading="save" delay="longest">Foo bar</x-card>')
    ->render()
    ->toContain('wire:loading.delay.longest')
    ->toContain('wire:target="save"');

it('does not render loading bar by default')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->not->toContain('wire:loading');

it('adds relative class when loading is set')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('relative');

it('renders loading bar with indeterminate animation')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('animate-indeterminate');

it('can render with event listeners on wrapper')
    ->expect('<x-card header="Test" minimize x-on:minimize="handleMinimize" x-on:maximize="handleMaximize">Foo bar</x-card>')
    ->render()
    ->toContain('x-on:minimize="handleMinimize"')
    ->toContain('x-on:maximize="handleMaximize"');

it('cannot use image and color together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [image] and [color] cannot be used together.');

    expect('<x-card image="https://via.placeholder.com/150" color="red">Foo bar</x-card>')
        ->render();
});

it('renders the default rounded-lg when round is absent')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->toContain('rounded-lg');

it('keeps the default rounded-lg when round is used as a flag')
    ->expect('<x-card round>Foo bar</x-card>')
    ->render()
    ->toContain('rounded-lg');

it('can render round with named size', function (string $size, string $class) {
    $component = "<x-card round=\"$size\">Foo bar</x-card>";

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain($class);
})->with([
    ['xs', 'rounded-xs'],
    ['sm', 'rounded-sm'],
    ['md', 'rounded-md'],
    ['lg', 'rounded-lg'],
    ['xl', 'rounded-xl'],
    ['2xl', 'rounded-2xl'],
]);

it('cannot accept invalid round value', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [round] must be true or one of: [xs, sm, md, lg, xl, 2xl].');

    expect('<x-card round="huge">Foo bar</x-card>')->render();
});

it('can render without body padding', function () {
    expect('<x-card paddingless>Foo bar</x-card>')->render()
        ->toContain('p-0!');
});

it('can render with body padding by default', function () {
    expect('<x-card>Foo bar</x-card>')->render()
        ->toContain('px-4 py-5')
        ->not->toContain('p-0!');
});

it('can render with shadow and without border by default')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->toContain('shadow-md')
    ->not->toContain('shadow-none!')
    ->not->toContain('border-gray-200');

it('can render shadowless')
    ->expect('<x-card shadowless>Foo bar</x-card>')
    ->render()
    ->toContain('shadow-none!');

it('can render bordered')
    ->expect('<x-card bordered>Foo bar</x-card>')
    ->render()
    ->toContain('border border-gray-200 dark:border-dark-600');

it('can render shadowless and bordered together')
    ->expect('<x-card shadowless bordered>Foo bar</x-card>')
    ->render()
    ->toContain('shadow-none!')
    ->toContain('border border-gray-200 dark:border-dark-600');

it('can render the accent header variation')
    ->expect('<x-card color="red" accent header="Foo">Bar</x-card>')
    ->render()
    ->toContain('border-t-4')
    ->toContain('border-t-red-600');

it('can render the background header variation by default')
    ->expect('<x-card color="red" header="Foo">Bar</x-card>')
    ->render()
    ->toContain('bg-red-600')
    ->not->toContain('border-t-4');

it('can align the footer slot', function (string $attribute, string $class) {
    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer {{ attribute }}>
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect(str_replace('{{ attribute }}', $attribute, $component))->render()
        ->toContain('flex items-center gap-2 '.$class);
})->with([
    ['', 'justify-end'],
    ['start', 'justify-start'],
    ['center', 'justify-center'],
    ['end', 'justify-end'],
    ['between', 'justify-between'],
]);

it('can align the footer attribute to the end by default', function () {
    expect('<x-card footer="Baz">Foo bar</x-card>')->render()
        ->toContain('flex items-center gap-2 justify-end');
});

it('can render the footer slot without the aligning wrapper', function () {
    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer unwrapped>
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect($component)->render()
        ->toContain('Baz')
        ->toContain('border-t border-t-gray-200')
        ->not->toContain('flex items-center gap-2');
});

it('can merge the footer slot attributes without leaking the alignment keywords', function () {
    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer between class="foo-bar-baz-bah">
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect($component)->render()
        ->toContain('foo-bar-baz-bah')
        ->toContain('justify-between')
        ->not->toContain('between="between"');
});

it('cannot let the footer slot attributes override the minimize behavior', function () {
    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer x-show="whatever">
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect($component)->render()
        ->toContain('<div x-show="!minimize"');
});

it('can thrown exception when the footer slot combines alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [footer] slot cannot combine the alignments [center, between]');

    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer center between>
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect($component)->render();
});

it('can thrown exception when the footer slot mixes unwrapped with alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [footer] slot cannot use [unwrapped] together with [start]');

    $component = <<<'HTML'
    <x-card>
    Foo bar
    <x-slot:footer unwrapped start>
        Baz
    </x-slot:footer>
    </x-card>
    HTML;

    expect($component)->render();
});
it('can render the skeleton instead of the content')
    ->expect('<x-card skeleton>Real content</x-card>')
    ->render()
    ->toContain('animate-pulse')
    ->not->toContain('Real content');

it('can render the skeleton with the default line count', function () {
    $html = Blade::render('<x-card skeleton />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(3);
});

it('can render the skeleton with a custom line count', function () {
    $html = Blade::render('<x-card skeleton="6" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
});

it('can render the skeleton with the header, the image and the footer', function () {
    $html = Blade::render(<<<'HTML'
    <x-card skeleton="2" header="Foo" image="https://foo.bar/baz.png">
        <x-slot:footer>Bar</x-slot:footer>
    </x-card>
    HTML);

    expect(substr_count($html, 'bg-gray-200'))->toBe(5);
});

it('can render the skeleton honoring the round size')
    ->expect('<x-card skeleton round="2xl" />')
    ->render()
    ->toContain('rounded-2xl');

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-card skeleton="0" />')->render();
});
