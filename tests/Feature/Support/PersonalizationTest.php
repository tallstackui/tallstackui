<?php

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Personalization\PersonalizationFactory;
use TallStackUi\View\Components\Alert;

use function Livewire\invade;

it('can be instantiated', function () {
    expect(TallStackUi::personalize())->toBeInstanceOf(Personalization::class);
});

it('can be instantiated with a component', function () {
    expect(TallStackUi::personalize('alert')
        ->block('wrapper', fn () => 'string'))
        ->toBeInstanceOf(PersonalizationFactory::class);
});

it('can instantiate all components', function (string $component) {
    expect(TallStackUi::personalize($component)->instance())->toBeInstanceOf(PersonalizationFactory::class);
})->with('personalizations.keys');

it('can personalize using facade and string', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::personalize('alert')
        ->block('wrapper', 'p-4');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');
});

it('can personalize using method and string', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');
});

it('can personalize using method and closure', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', fn () => 'p-4');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md');
});

it('can personalize using method and array', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::personalize()
        ->alert()
        ->block([
            'wrapper' => 'p-4',
        ]);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md');
});

it('can personalize in sequence', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    expect('<x-avatar label="Lorem" md />')->render()
        ->toContain('w-12 h-12');

    TallStackUi::personalize()
        ->alert()
        ->block('wrapper', 'p-4')
        ->and()
        ->avatar()
        ->block('wrapper.class', 'inline-flex shrink-0 items-center justify-center text-xl');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md');

    expect('<x-avatar label="Lorem" md />')->render()
        ->toContain('Lorem')
        ->not->toContain('overflow-hidden');
});

it('can personalize using append', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600')
        ->not->toContain('foo-bar-baz');

    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->append('foo-bar-baz');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');
});

it('can personalize using prepend', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600')
        ->not->toContain('foo-bar-baz');

    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->prepend('foo-bar-baz');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');
});

it('can personalize using remove', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600', 'rounded-lg');

    /* string */
    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->remove('rounded-lg');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');

    /* array */
    TallStackUi::personalize('alert')
        ->block('wrapper')
        ->remove(['rounded-lg', 'p-4']);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md', 'p-4');
});

it('can personalize using replace', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600', 'rounded-lg');

    /* array */
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace(['font-semibold' => 'foo-bar-baz']);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');

    /* from -> to */
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace('text-lg', 'baz-bar-foo');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'baz-bar-foo');
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

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz', 'baz-bar-foo');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('flex-wrap', 'justify-between');

    expect('<x-badge title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('text-xs');
});

it('can personalize components overriding the original', function () {
    $class = new class extends Alert {};

    config()->set('tallstackui.components.alert', $class);

    TallStackUi::personalize('alert')
        ->block('text.title')
        ->replace('font-semibold', 'foo-bar-baz')
        ->replace('text-lg', 'baz-bar-foo')
        ->and()
        ->alert()
        ->block('content.wrapper')
        ->remove('flex-wrap')
        ->remove('justify-between');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz', 'baz-bar-foo');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('flex-wrap', 'justify-between');
});

it('can personalize scoped using common - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.title', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using replace - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-xl', 'font-bold');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->replace('font-semibold', 'font-bold');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl', 'font-bold')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using remove - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->toContain('text-lg', 'font-semibold');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.title')
        ->remove('text-lg');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using append - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.title')
        ->append('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can personalize scoped using prepend - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.title')
        ->prepend('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can personalize scoped using multiple changes - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->toContain('text-lg', 'font-semibold', 'text-sm', 'flex-wrap');

    TallStackUi::personalize('alert', 'testing')
        ->block('wrapper')
        ->append('foo-bar')
        ->prepend('baz-bah')
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->replace('font-semibold', 'font-bold')
        ->remove('font-bold')
        ->block('text.description')
        ->remove('text-sm')
        ->block('content.wrapper')
        ->replace('flex-wrap', 'flex-col');

    $component = <<<'HTML'
    <x-alert title="Foo bar" text="Bar baz" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl', 'foo-bar', 'baz-bah', 'flex-col')
        ->not->toContain('font-bold', 'text-sm', 'flex-wrap');
});

it('can personalize scoped using common - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('text.title', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using replace - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-xl', 'font-bold');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->replace('font-semibold', 'font-bold');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl', 'font-bold')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using remove - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->toContain('text-lg', 'font-semibold');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->remove('text-lg');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-lg', 'font-semibold');
});

it('can personalize scoped using append - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->append('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can personalize scoped using prepend - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->prepend('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can personalize scoped using multiple changes - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->toContain('text-lg', 'font-semibold', 'text-sm', 'flex-wrap');

    TallStackUi::personalize(scope: 'testing')
        ->alert()
        ->block('wrapper')
        ->append('foo-bar')
        ->prepend('baz-bah')
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->replace('font-semibold', 'font-bold')
        ->remove('font-bold')
        ->block('text.description')
        ->remove('text-sm')
        ->block('content.wrapper')
        ->replace('flex-wrap', 'flex-col');

    $component = <<<'HTML'
    <x-alert title="Foo bar" text="Bar baz" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl', 'foo-bar', 'baz-bah', 'flex-col')
        ->not->toContain('font-bold', 'text-sm', 'flex-wrap');
});

it('can merge scoped and soft personalization', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('text-lg')
        ->not->toContain('text-xl');

    TallStackUi::personalize('alert', 'testing')
        ->block('text.description')
        ->replace('text-sm', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-lg')
        ->toContain('text-xl')
        ->not->toContain('text-sm');
});

it('can personalize scoped multiples components - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    $component = <<<'HTML'
    <x-badge text="Bar foo" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::personalize('alert', 'alert')
        ->block('text.title')
        ->replace('text-lg', 'text-xl');

    TallStackUi::personalize('badge', 'badge')
        ->block('wrapper.class')
        ->replace('border', 'text-xl');

    $alert = <<<'HTML'
    <x-alert title="Foo bar" scope="alert" />
    HTML;

    $badge = <<<'HTML'
    <x-badge text="Bar foo" scope="badge" />
    HTML;

    expect($alert)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg')
        ->and($badge)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg');
});

it('can personalize scoped multiples components - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    $component = <<<'HTML'
    <x-badge text="Bar foo" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::personalize(scope: 'alert')
        ->alert()
        ->block('text.title')
        ->replace('text-lg', 'text-xl');

    TallStackUi::personalize(scope: 'badge')
        ->badge()
        ->block('wrapper.class')
        ->replace('border', 'text-xl');

    $alert = <<<'HTML'
    <x-alert title="Foo bar" scope="alert" />
    HTML;

    $badge = <<<'HTML'
    <x-badge text="Bar foo" scope="badge" />
    HTML;

    expect($alert)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg')
        ->and($badge)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg');
});

it('can personalize scoped multiples components sequentially', function () {
    TallStackUi::personalize(scope: 'alert')
        ->alert()
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->and()
        ->badge(scope: 'badge')
        ->block('wrapper.class')
        ->replace('border', 'text-xl');

    $alert = <<<'HTML'
    <x-alert title="Foo bar" scope="alert" />
    HTML;

    $badge = <<<'HTML'
    <x-badge text="Bar foo" scope="badge" />
    HTML;

    expect($alert)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg')
        ->and($badge)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg');

    $alert = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    $badge = <<<'HTML'
    <x-badge text="Bar foo" scope="badge" />
    HTML;

    expect($alert)->render()
        ->not->toContain('text-xl')
        ->toContain('text-lg')
        ->and($badge)->render()
        ->toContain('border')
        ->not->toContain('text-lg');
});

it('cannot personalize wrong component', function () {
    $this->expectException(Exception::class);

    TallStackUi::personalize()
        ->form('input2')
        ->block('base2', 'rounded-md p-4');
});

it('cannot personalize wrong block', function () {
    $this->expectException(Exception::class);

    TallStackUi::personalize()
        ->alert()
        ->block('base2', 'rounded-md p-4');
});

it('cannot duplicated append or prepend personalizations', function () {
    TallStackUi::personalize('alert')
        ->block('text.title')
        ->append('foo-bar-baz')
        ->prepend('bar-baz-foo');

    $view = expect('<x-alert title="Foo bar" />')->render()->value;

    expect(str($view)->substrCount('foo-bar-baz'))
        ->toBe(1)
        ->and(str($view)->substrCount('bar-baz-foo'))
        ->toBe(1);
});

// This test is useful to verify that all customization
// keys are in use in the component's blade file.
it('cannot have unused personalization keys', function (string $component) {
    $class = new $component;
    $blade = invade($class->blade())->path;

    $content = file_get_contents($blade);

    foreach (array_keys($class->personalization()) as $key) {
        expect($content)->toContain($key);
    }
})->with('personalizations.components')->skip('This test is useful to verify that all customization keys are in use in the component\'s blade file.');
