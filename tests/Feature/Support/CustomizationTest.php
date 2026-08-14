<?php

use TallStackUi\Components\Alert\Component as Alert;
use TallStackUi\Components\Avatar\Group\Component;
use TallStackUi\Customization\Customization;
use TallStackUi\Customization\CustomizationFactory;
use TallStackUi\Facades\TallStackUi;

it('can be instantiated', function () {
    expect(TallStackUi::customize())->toBeInstanceOf(Customization::class);
});

it('can be instantiated with a component', function () {
    expect(TallStackUi::customize('alert')
        ->block('wrapper', fn () => 'string'))
        ->toBeInstanceOf(CustomizationFactory::class);
});

it('can instantiate all components', function (string $component) {
    expect(TallStackUi::customize($component)->forward())->toBeInstanceOf(CustomizationFactory::class);
})->with('customization.keys');

it('can load the original blocks of all components', function (string $component) {
    // forward() alone never touches the component: the blocks are only loaded
    // once a block is interacted with, which is what every customization does.
    $factory = TallStackUi::customize($component)->forward()->block('probe');

    expect($factory->blocks)->not->toBeEmpty();
})->with('customization.keys');

it('can customize using facade and string', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::customize('alert')
        ->block('rounded.lg', 'rounded-md');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');
});

it('can customize using method and string', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::customize()
        ->alert()
        ->block('rounded.lg', 'rounded-md');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');
});

it('can customize using method and closure', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::customize()
        ->alert()
        ->block('wrapper', fn () => 'p-4');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md');
});

it('can customize using method and array', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    TallStackUi::customize()
        ->alert()
        ->block([
            'wrapper' => 'p-4',
        ]);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-md');
});

it('can customize in sequence', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600');

    expect('<x-avatar label="Lorem" md />')->render()
        ->toContain('w-12 h-12');

    TallStackUi::customize()
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

it('can customize using append', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600')
        ->not->toContain('foo-bar-baz');

    TallStackUi::customize('alert')
        ->block('wrapper')
        ->append('foo-bar-baz');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');
});

it('can customize using prepend', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600')
        ->not->toContain('foo-bar-baz');

    TallStackUi::customize('alert')
        ->block('wrapper')
        ->prepend('foo-bar-baz');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');
});

it('can customize using remove', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600', 'rounded-lg');

    /* string */
    TallStackUi::customize('alert')
        ->block('rounded.lg')
        ->remove('rounded-lg');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');

    /* array */
    TallStackUi::customize('alert')
        ->block('rounded.lg')
        ->remove(['rounded-lg']);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar')
        ->not->toContain('rounded-lg');
});

it('can customize using replace', function () {
    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'bg-primary-600', 'rounded-lg');

    /* array */
    TallStackUi::customize('alert')
        ->block('text.title')
        ->replace(['font-semibold' => 'foo-bar-baz']);

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'foo-bar-baz');

    /* from -> to */
    TallStackUi::customize('alert')
        ->block('text.title')
        ->replace('text-lg', 'baz-bar-foo');

    expect('<x-alert title="Foo bar" />')->render()
        ->toContain('Foo bar', 'baz-bar-foo');
});

it('can customize chained', function () {
    TallStackUi::customize('alert')
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

it('can customize components overriding the original', function () {
    $class = new class extends Alert {};

    config()->set('ts-ui.components.alert', $class);

    TallStackUi::customize('alert')
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

it('can customize scoped using common - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::customize('alert', 'testing')
        ->block('text.title', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can customize scoped using replace - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-xl', 'font-bold');

    TallStackUi::customize('alert', 'testing')
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

it('can customize scoped using remove - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->toContain('text-lg', 'font-semibold');

    TallStackUi::customize('alert', 'testing')
        ->block('text.title')
        ->remove('text-lg');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-lg', 'font-semibold');
});

it('can customize scoped using append - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::customize('alert', 'testing')
        ->block('text.title')
        ->append('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can customize scoped using prepend - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::customize('alert', 'testing')
        ->block('text.title')
        ->prepend('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can customize scoped using multiple changes - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->toContain('text-lg', 'font-semibold', 'text-sm', 'flex-wrap');

    TallStackUi::customize('alert', 'testing')
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

it('can customize scoped using common - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::customize(scope: 'testing')
        ->alert()
        ->block('text.title', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg', 'font-semibold');
});

it('can customize scoped using replace - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-xl', 'font-bold');

    TallStackUi::customize(scope: 'testing')
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

it('can customize scoped using remove - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->toContain('text-lg', 'font-semibold');

    TallStackUi::customize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->remove('text-lg');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->not->toContain('text-lg', 'font-semibold');
});

it('can customize scoped using append - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::customize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->append('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can customize scoped using prepend - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->not->toContain('transition-all');

    TallStackUi::customize(scope: 'testing')
        ->alert()
        ->block('text.title')
        ->prepend('transition-all');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="testing" />
    HTML;

    expect($component)->render()
        ->toContain('transition-all');
});

it('can customize scoped using multiple changes - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()
        ->toContain('text-lg', 'font-semibold', 'text-sm', 'flex-wrap');

    TallStackUi::customize(scope: 'testing')
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

    TallStackUi::customize('alert', 'testing')
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

it('can customize scoped multiples components - component as string', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    $component = <<<'HTML'
    <x-badge text="Bar foo" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::customize('alert', 'alert')
        ->block('text.title')
        ->replace('text-lg', 'text-xl');

    TallStackUi::customize('badge', 'badge')
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

it('can customize scoped multiples components - component as method', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    $component = <<<'HTML'
    <x-badge text="Bar foo" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::customize(scope: 'alert')
        ->alert()
        ->block('text.title')
        ->replace('text-lg', 'text-xl');

    TallStackUi::customize(scope: 'badge')
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

it('can customize scoped multiples components sequentially', function () {
    TallStackUi::customize(scope: 'alert')
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

it('can set the scope using method', function () {
    TallStackUi::customize()
        ->scope('alert')
        ->alert()
        ->block('text.title')
        ->replace('text-lg', 'text-xl')
        ->and()
        ->scope('badge')
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

it('can customize scoped with dotted scope name', function () {
    $component = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($component)->render()->not->toContain('text-xl');

    TallStackUi::customize('alert', 'form.color.input')
        ->block('text.title')
        ->replace('text-lg', 'text-xl');

    $component = <<<'HTML'
    <x-alert title="Foo bar" scope="form.color.input" />
    HTML;

    expect($component)->render()
        ->toContain('text-xl')
        ->not->toContain('text-lg');

    $unscoped = <<<'HTML'
    <x-alert title="Foo bar" />
    HTML;

    expect($unscoped)->render()
        ->toContain('text-lg')
        ->not->toContain('text-xl');
});

it('cannot customize wrong component', function () {
    $this->expectException(Exception::class);

    TallStackUi::customize()
        ->form('input2')
        ->block('base2', 'rounded-md p-4');
});

it('cannot customize wrong block', function () {
    $this->expectException(Exception::class);

    TallStackUi::customize()
        ->alert()
        ->block('base2', 'rounded-md p-4');
});

it('cannot duplicated append or prepend customization', function () {
    TallStackUi::customize('alert')
        ->block('text.title')
        ->append('foo-bar-baz')
        ->prepend('bar-baz-foo');

    $view = expect('<x-alert title="Foo bar" />')->render()->value;

    expect(str($view)->substrCount('foo-bar-baz'))
        ->toBe(1)
        ->and(str($view)->substrCount('bar-baz-foo'))
        ->toBe(1);
});

it('can stack customizations of the same block across separate chains', function () {
    TallStackUi::customize('alert')
        ->block('wrapper')
        ->append('from-a');

    TallStackUi::customize('alert')
        ->block('wrapper')
        ->append('from-b');

    expect(app('ts-ui::customization.alert')->get('wrapper'))
        ->toContain('from-a')
        ->toContain('from-b');
});

it('can remove a class without touching the ones that contain it', function () {
    TallStackUi::customize('step')
        ->block('panels-shape')
        ->remove('border');

    expect(app('ts-ui::customization.step')->get('panels-shape'))
        ->not->toContain(' border ')
        ->toContain('border-gray-200')
        ->toContain('dark:border-dark-700');
});

it('can remove more than one class from a single string', function () {
    TallStackUi::customize('step')
        ->block('panels-shape')
        ->remove('rounded-md border');

    expect(app('ts-ui::customization.step')->get('panels-shape'))
        ->not->toContain('rounded-md')
        ->toContain('border-gray-200');
});

it('can chain a shortcut after replacing the whole block', function () {
    TallStackUi::customize('alert')
        ->block('wrapper', 'p-8')
        ->append('foo-bar');

    expect(app('ts-ui::customization.alert')->get('wrapper'))
        ->toBe('p-8 foo-bar');
});

it('cannot use a shortcut before choosing a block', function () {
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('No block has been set.');

    app('ts-ui::customization.alert')->append('foo-bar');
});

it('can read a block of a scoped customization', function () {
    TallStackUi::customize('alert', scope: 'foo')
        ->block('wrapper')
        ->append('bar-baz');

    $key = __ts_scope_container_key('ts-ui::customization.alert', 'foo');

    expect(app($key)->get('wrapper'))->toContain('bar-baz');
});

it('can extend a scope that has already been defined', function () {
    TallStackUi::customize()
        ->scope('shadowless')
        ->card()
        ->block('wrapper.second')
        ->remove('shadow-md')
        ->append('border border-gray-200');

    TallStackUi::customize()
        ->extend(scope: 'shadowless')
        ->card()
        ->block('wrapper.second')
        ->append('foo-bar');

    $key = __ts_scope_container_key('ts-ui::customization.card', 'shadowless');

    expect(app($key)->get('wrapper.second'))
        ->not->toContain('shadow-md')
        ->toContain('border border-gray-200')
        ->toContain('foo-bar');
});

it('can extend one of the predefined scopes', function () {
    TallStackUi::customize()
        ->extend(scope: 'card-shadowless')
        ->card()
        ->block('wrapper.second')
        ->append('foo-bar');

    expect('<x-card scope="card-shadowless">Foo</x-card>')->render()
        ->toContain('foo-bar')
        ->toContain('border border-gray-200')
        ->not->toContain('shadow-md');
});

it('cannot extend a scope that was never defined', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The scope [nope] was not defined for the component [card] and therefore cannot be extended.');

    TallStackUi::customize()
        ->extend(scope: 'nope')
        ->card()
        ->block('wrapper.second')
        ->append('foo-bar');
});

it('can customize the avatar group through its own key', function () {
    expect(TallStackUi::customize('avatar.group')->forward()->component)
        ->toBe(Component::class);
});

it('cannot pass a sub-component to a component that has none', function () {
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('The component [badge] does not have the sub-component [main]');

    TallStackUi::customize('badge.main')->forward();
});

it('names the component by its customization key when the block is unknown', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Component [badge] does not have the block [nope]');

    TallStackUi::customize('badge')->block('nope', 'foo');
});

it('keeps the global customization on a scoped instance', function () {
    TallStackUi::customize('alert')
        ->block('wrapper')
        ->append('brand-shadow');

    TallStackUi::customize('alert', 'flat')
        ->block('text.title')
        ->append('text-xl');

    expect('<x-alert title="Foo bar" />')->render()->toContain('brand-shadow');

    // The scope only spoke about text.title, so every other block has to keep
    // whatever the global customization did to it.
    expect('<x-alert title="Foo bar" scope="flat" />')->render()
        ->toContain('brand-shadow')
        ->toContain('text-xl');
});

it('does not let a scoped block swallow its dot notation sibling', function () {
    TallStackUi::customize('card', 'flat')->block([
        'body' => 'grow px-2 py-2',
        'body.paddingless' => 'p-0!',
    ]);

    expect('<x-card scope="flat" paddingless>Foo bar</x-card>')->render()
        ->toContain('p-0!');

    expect('<x-card scope="flat">Foo bar</x-card>')->render()
        ->toContain('px-2 py-2');
});
