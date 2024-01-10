<?php

use TallStackUi\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | Control a prefix for the TallStackUI components. The term here will be used
    | to prefix all TallStackUI components. This is useful to avoid conflicts
    | with other components registered by other libraries or created by yourself.
    |
    | For example: prefixing as 'ts-', the `alert` usage will be: '<x-ts-alert />'
    */
    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Control the debug mode for TallStackUI components.
    */
    'debug' => [
        'status' => env('TALLSTACKUI_DEBUG_MODE', false),
        /*
        |----------------------------------------------------------------------
        | You can control in which environments the debug mode is enabled.
        |----------------------------------------------------------------------
        */
        'environments' => [
            'local',
            'sandbox',
            'staging',
        ],
        /*
        |----------------------------------------------------------------------
        | You can ignore debug mode for certain specific components
        | by setting the exact component name in this array.
        |----------------------------------------------------------------------
        */
        'ignore' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Style
    |--------------------------------------------------------------------------
    |
    | Control the default icon style (Allowed: solid, outline)
    */
    'icon' => 'solid',

    /*
    |--------------------------------------------------------------------------
    | Component Settings
    |--------------------------------------------------------------------------
    |
    | General components settings.
    */
    'settings' => [
        /*
        |----------------------------------------------------------------------
        | Dialog
        |----------------------------------------------------------------------
        | z-index: controls the default z-index.
        | blur: enables the background blur effect by default.
        | persistent: enables the dialog to not be closed by clicking outside by default.
        */
        'dialog' => [
            'z-index' => 'z-50',
            'blur' => false,
            'persistent' => false,
        ],
        /*
        |----------------------------------------------------------------------
        | Modal
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | blur: enables the background blur effect by default.
        | persistent: enables the modal to not be closed by clicking outside by default.
        | size: controls the default modal size (Allowed: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl).
        | center: controls if the modal is centered by default.
        */
        'modal' => [
            'z-index' => 'z-50',
            'blur' => false,
            'persistent' => false,
            'size' => '2xl',
            'center' => false,
        ],
        /*
        |----------------------------------------------------------------------
        | Loading
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | blur: enables the background blur effect by default.
        | opacity: enables the background opacity by default.
        */
        'loading' => [
            'z-index' => 'z-50',
            'blur' => false,
            'opacity' => true,
        ],
        /*
        |----------------------------------------------------------------------
        | Slide
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | blur: enables the background blur effect by default.
        | persistent: enables the slide to not be closed by clicking outside by default.
        | size: controls the default modal size (Allowed: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full).
        | position: controls the default slide position (Allowed: right, left).
        */
        'slide' => [
            'z-index' => 'z-50',
            'blur' => false,
            'persistent' => false,
            'size' => 'lg',
            'position' => 'right',
        ],
        /*
        |----------------------------------------------------------------------
        | Toast
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | progress: enables the progress bar.
        | expandable: enables the expand effect by default.
        | position: controls the default toast position (Allowed: top-right, top-left, bottom-right, bottom-left).
        */
        'toast' => [
            'z-index' => 'z-50',
            'progress' => true,
            'expandable' => false,
            'position' => 'top-right',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Component List
    |--------------------------------------------------------------------------
    |
    | List of all TallStackUI components.
    */
    'components' => [
        'alert' => Components\Alert::class,
        'avatar' => Components\Avatar::class,
        'badge' => Components\Badge::class,
        'banner' => Components\Banner::class,
        'button' => Components\Button\Button::class,
        'button.circle' => Components\Button\Circle::class,
        'card' => Components\Card::class,
        'checkbox' => Components\Form\Checkbox::class,
        'color' => Components\Form\Color::class,
        'clipboard' => Components\Clipboard::class,
        'dialog' => Components\Interaction\Dialog::class,
        'dropdown' => Components\Dropdown\Dropdown::class,
        'dropdown.items' => Components\Dropdown\Items::class,
        'error' => Components\Form\Error::class,
        'errors' => Components\Errors::class,
        'hint' => Components\Form\Hint::class,
        'icon' => Components\Icon::class,
        'input' => Components\Form\Input::class,
        'label' => Components\Form\Label::class,
        'link' => Components\Link::class,
        'loading' => Components\Loading::class,
        'modal' => Components\Modal::class,
        'number' => Components\Form\Number::class,
        'password' => Components\Form\Password::class,
        'pin' => Components\Form\Pin::class,
        'radio' => Components\Form\Radio::class,
        'range' => Components\Form\Range::class,
        'select.native' => Components\Select\Native::class,
        'select.styled' => Components\Select\Styled::class,
        'slide' => Components\Slide::class,
        'tab' => Components\Tab\Tab::class,
        'tag' => Components\Form\Tag::class,
        'table' => Components\Table::class,
        'tab.items' => Components\Tab\Items::class,
        'textarea' => Components\Form\Textarea::class,
        'toast' => Components\Interaction\Toast::class,
        'toggle' => Components\Form\Toggle::class,
        'tooltip' => Components\Tooltip::class,
        'wrapper.input' => Components\Wrapper\Input::class,
        'wrapper.radio' => Components\Wrapper\Radio::class,
    ],
];
