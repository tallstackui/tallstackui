<?php

use Illuminate\View\Component;
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
        'classes',
        'render',
        'compile',
        'output',
    ]);

    test('all components should extends base component', function (string $component) {
        expect($component)->toExtend(BaseComponent::class);
    })->with('personalizations.components');
});
