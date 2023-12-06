<?php

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;
use TallStackUi\View\Personalizations\Personalization;

it('can be instantiated', function () {
    expect(TallStackUi::personalize())->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component', function () {
    expect(TallStackUi::personalize('alert')
        ->block('wrapper', fn () => 'string'))
        ->toBeInstanceOf(PersonalizableResources::class);
});

it('can instantiate all components', function (string $component) {
    expect(TallStackUi::personalize($component)->instance())->toBeInstanceOf(PersonalizableResources::class);
})->with('personalizations.keys');

it('can personalize using facade and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');

    TallStackUi::personalize('alert')
        ->block('wrapper', 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-lg');
});

it('can personalize using method and string', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-lg');
});

it('can personalize using method and closure', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', fn () => 'p-4');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize using method and array', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block([
            'wrapper' => 'p-4',
        ]);

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can personalize in sequence', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('w-12 h-12');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4')
        ->and()
        ->avatar()
        ->block('wrapper.class', 'inline-flex shrink-0 items-center justify-center text-xl');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');

    $this->blade('<x-avatar label="Lorem" md />')
        ->assertSee('Lorem')
        ->assertDontSee('overflow-hidden');
});

it('can personalize using append', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600')
        ->assertDontSee('foo-bar-baz');

    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->append('foo-bar-baz');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('foo-bar-baz');
});

it('can personalize using prepend', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600')
        ->assertDontSee('foo-bar-baz');

    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->prepend('foo-bar-baz');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('foo-bar-baz');
});

it('can personalize using remove', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600')
        ->assertSee('rounded-lg');

    /* string */
    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->remove('rounded-lg');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-lg');

    /* array */
    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->remove(['rounded-lg', 'p-4']);

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md')
        ->assertDontSee('p-4');
});

it('can personalize using replace', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600')
        ->assertSee('rounded-lg');

    /* array */
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace(['font-semibold' => 'foo-bar-baz']);

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('foo-bar-baz');

    /* from -> to */
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace('text-lg', 'baz-bar-foo');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('baz-bar-foo');
});

it('can personalize chained', function () {
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace('font-semibold', 'foo-bar-baz')
        ->replace('text-lg', 'baz-bar-foo')
        ->and()
        ->alert()
        ->block('content.wrapper')
        ->remove('flex-wrap')
        ->remove('justify-between')
        ->and
        ->badge()
        ->block('wrapper.sizes.xs')
        ->replace('text-xs', 'text-2xl');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('foo-bar-baz')
        ->assertSee('baz-bar-foo');

    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('flex-wrap')
        ->assertDontSee('justify-between');

    $this->blade('<x-badge title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertDontSee('text-xs');
});

it('cannot personalize wrong component', function () {
    $this->expectException(InvalidArgumentException::class);

    TallStackUi::personalize()
        ->form('input2')
        ->block('base2', 'rounded-md p-4');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    TallStackUi::personalize()
        ->alert()
        ->block('base2', 'rounded-md p-4');
});

it('cannot duplicated append or prepend personalizations', function () {
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->append('foo-bar-baz')
        ->prepend('bar-baz-foo');

    $view = $this->blade('<x-alert title="Foo bar" />')->__toString();

    expect(str($view)->substrCount('foo-bar-baz'))
        ->toBe(1)
        ->and(str($view)->substrCount('bar-baz-foo'))
        ->toBe(1);
});
