<?php

use Illuminate\View\Component;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesBindProperty;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesClasses;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesCompilation;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesOutput;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesRender;
use TallStackUi\View\Components\BaseComponent;

describe('BaseComponent', function () {
    test('BaseComponent should be abstract')
        ->expect(BaseComponent::class)
        ->toBeAbstract();

    test('BaseComponent should extends Component')
        ->expect(BaseComponent::class)
        ->toExtend(Component::class);

    test('BaseComponent should have all the expected methods', function (string $method) {
        expect(BaseComponent::class)
            ->toHaveMethod($method);
    })->with([
        'blade',
        'bind',
        'classes',
        'render',
        'compile',
        'output',
    ]);

    test('BaseComponent should implement traits', function (string $trait) {
        expect(BaseComponent::class)->toUse($trait);
    })->with([
        ManagesBindProperty::class,
        ManagesClasses::class,
        ManagesCompilation::class,
        ManagesOutput::class,
        ManagesRender::class,
    ]);

    test('BaseComponent traits should only be used in the BaseComponent', function (string $trait) {
        expect($trait)->toOnlyBeUsedIn(BaseComponent::class);
    })->with([
        ManagesBindProperty::class,
        ManagesClasses::class,
        ManagesCompilation::class,
        ManagesOutput::class,
        ManagesRender::class,
    ]);

    test('all components should extends base component', function (string $component) {
        expect($component)->toExtend(BaseComponent::class);
    })->with('personalizations.components');
});
