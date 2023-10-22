<?php

use TallStackUi\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Icon Style
    |--------------------------------------------------------------------------
    |
    | Configure the default icon style. Alloweds: "solid", "outline"
    */
    'icon' => 'solid',

    /*
    |--------------------------------------------------------------------------
    | Component Settings
    |--------------------------------------------------------------------------
    |
    | General components settings.
    */
    'personalizations' => [
        'input' => [
            /* Input, Textarea & Password */
            'square' => false,
            /* Input & Password */
            'round' => false,
        ],
        'tab' => [
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
            /* Alloweds: "top-right", "top-left", "bottom-right", "bottom-left" */
            'position' => 'top-right',
            'square' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Component List
    |--------------------------------------------------------------------------
    |
    | List of all TallStackUi components.
    */
    'components' => [
        'alert' => Components\Alert::class,
        'avatar' => Components\Avatar::class,
        'badge' => Components\Badge::class,
        'button' => Components\Button\Button::class,
        'button.circle' => Components\Button\Circle::class,
        'card' => Components\Card::class,
        'checkbox' => Components\Form\Checkbox::class,
        'dialog' => Components\Interaction\Dialog::class,
        'dropdown' => Components\Dropdown\Dropdown::class,
        'dropdown.items' => Components\Dropdown\Items::class,
        'error' => Components\Error::class,
        'errors' => Components\Errors::class,
        'hint' => Components\Hint::class,
        'icon' => Components\Icon::class,
        'input' => Components\Form\Input::class,
        'label' => Components\Form\Label::class,
        'modal' => Components\Modal::class,
        'password' => Components\Form\Password::class,
        'radio' => Components\Form\Radio::class,
        'select.native' => Components\Select\Select::class,
        'select.searchable' => Components\Select\Searchable::class,
        'select.styled' => Components\Select\Styled::class,
        'select.style' => Components\Select\Style::class,
        'tab' => Components\Tab\Tab::class,
        'tab.items' => Components\Tab\Items::class,
        'textarea' => Components\Form\Textarea::class,
        'toast' => Components\Interaction\Toast::class,
        'toggle' => Components\Form\Toggle::class,
        'tooltip' => Components\Tooltip::class,
        'wrapper.input' => Components\Wrapper\Input::class,
        'wrapper.radio' => Components\Wrapper\Radio::class,
        'wrapper.select' => Components\Wrapper\Select::class,
    ],
];
