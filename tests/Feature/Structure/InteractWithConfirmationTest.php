<?php

use TallStackUi\Foundation\Support\Interactions\Dialog;
use TallStackUi\Foundation\Support\Interactions\Toast;
use TallStackUi\Foundation\Support\Interactions\Traits\InteractWithConfirmation;

test('can only be used in Dialog and Toast')
    ->expect(InteractWithConfirmation::class)
    ->toOnlyBeUsedIn([
        Dialog::class,
        Toast::class,
    ]);
