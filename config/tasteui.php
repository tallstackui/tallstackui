<?php

use TasteUi\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Icon Style
    |--------------------------------------------------------------------------
    |
    | When configured, define the default icon type.
    |
    | Supported: "solid", "outline"
    */
    'icon' => null,

    /*
    |--------------------------------------------------------------------------
    | List of Components
    |--------------------------------------------------------------------------
    |
    | <usage> => <structure>
    */
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
        'select' => Components\Select\Select::class,
        'select.styled' => Components\Select\Styled::class,
        'select.searchable' => Components\Select\Searchable::class,
        'modal' => Components\Modal::class,
        'toast' => Components\Interactions\Toast::class,
        'dialog' => Components\Interactions\Dialog::class,
    ],

    'wrappers' => [
        'form' => [
            'input' => [
                'div' => 'relative mt-2 rounded-md shadow-sm',
                'span' => 'mt-2 text-sm text-secondary-500',
            ],
            'radio-toggle' => [
                'span' => 'relative inline-flex cursor-pointer items-center',
            ],
        ],
    ],
];
