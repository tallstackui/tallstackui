<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    $bag = new ViewErrorBag;

    $bag->put('default', new MessageBag(['name' => ['The name field is required.']]));

    View::share('errors', $bag);
});

it('can render with a string footer', function () {
    expect('<x-errors footer="Need help?" />')->render()
        ->toContain('<p class="mt-2">Need help?</p>');
});

it('can align the footer slot', function (string $attribute, string $class) {
    $component = <<<'HTML'
    <x-errors>
        <x-slot:footer {{ attribute }}>
            Fix now
        </x-slot:footer>
    </x-errors>
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

it('can render the footer slot without the aligning wrapper', function () {
    $component = <<<'HTML'
    <x-errors>
        <x-slot:footer unwrapped>
            Fix now
        </x-slot:footer>
    </x-errors>
    HTML;

    expect($component)->render()
        ->toContain('Fix now')
        ->toContain('class="mt-2"')
        ->not->toContain('flex gap-2');
});

it('can merge the footer slot attributes without leaking the alignment keywords', function () {
    $component = <<<'HTML'
    <x-errors>
        <x-slot:footer between class="foo-bar-baz-bah">
            Fix now
        </x-slot:footer>
    </x-errors>
    HTML;

    expect($component)->render()
        ->toContain('foo-bar-baz-bah')
        ->toContain('justify-between')
        ->not->toContain('between="between"');
});

it('can thrown exception when the footer slot combines alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Errors: The [footer] slot cannot combine the alignments [center, end]');

    $component = <<<'HTML'
    <x-errors>
        <x-slot:footer center end>
            Fix now
        </x-slot:footer>
    </x-errors>
    HTML;

    expect($component)->render();
});

it('can thrown exception when the footer slot mixes unwrapped with alignments', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Errors: The [footer] slot cannot use [unwrapped] together with [start]');

    $component = <<<'HTML'
    <x-errors>
        <x-slot:footer unwrapped start>
            Fix now
        </x-slot:footer>
    </x-errors>
    HTML;

    expect($component)->render();
});

it('can thrown exception when the title is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Errors: The [title] cannot be empty');

    expect('<x-errors title="" />')->render();
});

it('can render paddingless', function () {
    expect('<x-errors paddingless />')->render()->toContain('px-0!');
});

it('can render shadowless', function () {
    expect('<x-errors shadowless />')->render()->toContain('shadow-none!');
});

it('can render bordered', function () {
    expect('<x-errors bordered />')->render()->toContain('border-red-200');
});

it('can filter the messages using only', function (string $only) {
    $bag = new ViewErrorBag;

    $bag->put('default', new MessageBag([
        'name' => ['The name field is required.'],
        'description' => ['The description field is required.'],
        'email' => ['The email field is required.'],
    ]));

    View::share('errors', $bag);

    expect(str_replace('{{ only }}', $only, '<x-errors {{ only }} />'))->render()
        ->toContain('The name field is required.')
        ->toContain('The description field is required.')
        ->not->toContain('The email field is required.');
})->with([
    'only="name,description"',
    'only="name, description"',
    ':only="[\'name\', \'description\']"',
    ':only="collect([\'name\', \'description\'])"',
]);

it('can filter the messages using only with a single field', function (string $only) {
    expect(str_replace('{{ only }}', $only, '<x-errors {{ only }} />'))->render()
        ->toContain('The name field is required.');
})->with([
    'only="name"',
    ':only="[\'name\']"',
    ':only="collect([\'name\'])"',
]);
