<?php

use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesBindProperty;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesClasses;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesCompilation;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesOutput;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesRender;
use TallStackUi\TallStackUiComponent;

test('TallStackUiComponent should be abstract')
    ->expect(TallStackUiComponent::class)
    ->toBeAbstract();

test('TallStackUiComponent should extends Component')
    ->expect(TallStackUiComponent::class)
    ->toExtend(Component::class);

test('TallStackUiComponent should have all the expected methods', function (string $method) {
    expect(TallStackUiComponent::class)->toHaveMethod($method);
})->with([
    'blade',
    'bind',
    'classes',
    'render',
    'compile',
    'output',
]);

test('TallStackUiComponent traits should only be used in the TallStackUiComponent', function (string $trait) {
    expect($trait)->toOnlyBeUsedIn(TallStackUiComponent::class);
})->with([
    ManagesBindProperty::class,
    ManagesClasses::class,
    ManagesCompilation::class,
    ManagesOutput::class,
    ManagesRender::class,
]);

test('all components should extends base component', function (string $component) {
    expect($component)->toExtend(TallStackUiComponent::class);
})->with('personalizations.components');
