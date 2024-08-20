<?php

use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;
use TallStackUi\Actions\Traits\InteractWithConfirmation;

test('can only be used in Dialog and Toast')
    ->expect(InteractWithConfirmation::class)
    ->toOnlyBeUsedIn([
        Dialog::class,
        Toast::class,
    ]);
