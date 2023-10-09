<?php

use TallStackUi\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Icon Style
    |--------------------------------------------------------------------------
    |
    | Configure the default icon style. Supported: "solid", "outline"
    */
    'icon' => 'solid',

    /*
    |--------------------------------------------------------------------------
    | Components Settings
    |--------------------------------------------------------------------------
    |
    | General components settings.
    */
    'personalizations' => [
        'input' => [
            'square' => false,
            'round' => false,
        ],
        'tabs' => [
            'square' => false,
        ],
        'dialog' => [
            'z-index' => 'z-50',
            'blur' => false,
            'uncloseable' => false,
            'square' => false,
        ],
        'toast' => [
            'z-index' => 'z-50',
            'position' => 'top-right',
            'square' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Components List
    |--------------------------------------------------------------------------
    |
    | List of all TallStackUi components.
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
        'hint' => Components\Hint::class,
        'error' => Components\Error::class,
        'errors' => Components\Errors::class,
        'tooltip' => Components\Tooltip::class,
        'button' => Components\Button\Index::class,
        'button.circle' => Components\Button\Circle::class,
        'select' => Components\Select\Select::class,
        'select.styled' => Components\Select\Styled::class,
        'select.searchable' => Components\Select\Searchable::class,
        'modal' => Components\Modal::class,
        'toast' => Components\Interaction\Toast::class,
        'dialog' => Components\Interaction\Dialog::class,
        'wrapper.input' => Components\Wrapper\Input::class,
        'wrapper.radio' => Components\Wrapper\Radio::class,
        'wrapper.select' => Components\Wrapper\Select::class,
        'tabs' => Components\Tabs\Index::class,
        'tabs.items' => Components\Tabs\Items::class,
    ],
];
