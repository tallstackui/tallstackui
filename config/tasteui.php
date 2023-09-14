<?php

use TasteUi\View\Components;

return [
    'components' => [
        'input' => Components\Form\Input::class,
        'textarea' => Components\Form\Textarea::class,
        'password' => Components\Form\Password::class,
        'toggle' => Components\Form\Toggle::class,
        'radio' => Components\Form\Radio::class,
        'checkbox' => Components\Form\Checkbox::class,
        'icon' => Components\Icon::class,
        'label' => Components\Form\Label::class,
        'alert' => Components\Alert::class,
        'card' => Components\Card::class,
        'badge' => Components\Badge::class,
        'avatar' => Components\Avatar\Index::class,
        'avatar.modelable' => Components\Avatar\Modelable::class,
        'errors' => Components\Errors::class,
        'tooltip' => Components\Tooltip::class,
        'button' => Components\Button\Index::class,
        'button.circle' => Components\Button\Circle::class,
        'select' => Components\Select\Index::class,
    ],
];
