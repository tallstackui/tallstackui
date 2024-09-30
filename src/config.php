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
    'prefix' => env('TALLSTACKUI_PREFIX'),

    /*
    |--------------------------------------------------------------------------
    | Assets Fallback
    |--------------------------------------------------------------------------
    |
    | Controls the fallback behavior for loading assets.
    |
    | MAKE SURE TO READ THE DOCS BEFORE MANIPULATING THIS.
    */
    'assets_fallback' => env('TALLSTACKUI_ASSETS_FALLBACK', true),

    /*
    |--------------------------------------------------------------------------
    | Color Classes Namespace
    |--------------------------------------------------------------------------
    |
    | The namespace related to classes used for component color personalization.
    */
    'color_classes_namespace' => env('TALLSTACKUI_COLOR_CLASSES_NAMESPACE', 'App\\View\\Components\\TallStackUi\\Colors'),

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
        | Controls the environments where the debug mode can be enabled.
        |----------------------------------------------------------------------
        */
        'environments' => [
            'local',
            'sandbox',
            'staging',
        ],

        /*
        |----------------------------------------------------------------------
        | Ignore debug mode for specific components.
        |----------------------------------------------------------------------
        */
        'ignore' => [
            // Components\Alert::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Style
    |--------------------------------------------------------------------------
    */
    'icons' => [
        /*
        |----------------------------------
        | Default and in-use icon type.
        |----------------------------------
        | Allowed: heroicons, phosphoricons, google, tablericons.
        */
        'type' => env('TALLSTACKUI_ICON_TYPE', 'heroicons'),

        /*
        |----------------------------------
        | Default and in-use icon style.
        |----------------------------------
        | Allowed:
        |
        | Heroicons: solid, outline
        | Phosphoricons: thin, light, regular, bold, duotone
        | Google: default
        | Tablericons: default
        */
        'style' => env('TALLSTACKUI_ICON_STYLE', 'solid'),

        /*
        |----------------------------------
        | Flush unused icons pack.
        |----------------------------------
        |
        | To avoid the accumulation of unused files, the icon packs that are
        | not in use can be deleted automatically when new icons are set.
        */
        'flush' => true,

        /*
        |----------------------------------
        | Custom icon configuration.
        |----------------------------------
        */
        'custom' => [
            /*
            |----------------------------------
            | Custom icons guide.
            |----------------------------------
            |
            | These icons are used internally in the components. When using custom
            | icons you can optionally change the internal icons to custom icons,
            | causing this to reflect new icon looks for the internal components.
            */
            'guide' => [
                'arrow-path' => null,
                'arrow-trending-up' => null,
                'arrow-trending-down' => null,
                'arrow-up-tray' => null,
                'calendar' => null,
                'check' => null,
                'check-circle' => null,
                'chevron-down' => null,
                'chevron-left' => null,
                'chevron-right' => null,
                'chevron-up' => null,
                'chevron-up-down' => null,
                'clipboard' => null,
                'clipboard-document' => null,
                'cloud-arrow-up' => null,
                'clock' => null,
                'document-check' => null,
                'document-text' => null,
                'exclamation-circle' => null,
                'eye' => null,
                'eye-slash' => null,
                'information-circle' => null,
                'magnifying-glass' => null,
                'minus' => null,
                'moon' => null,
                'photo' => null,
                'plus' => null,
                'question-mark-circle' => null,
                'swatch' => null,
                'sun' => null,
                'trash' => null,
                'x-circle' => null,
                'x-mark' => null,
            ],

            /*
            |----------------------------------
            | Custom icon fallback behavior.
            |----------------------------------
            |
            | When enabling it we will use internal icons (Heroicons) to avoid
            | exceptions when using a custom icon that doesn't exist. For example,
            | if you use a "check" icon and don't have a custom one "check" icon,
            | the Heroicons "check" icon will be used. Preferably you should only
            | disable this when you are customizing ALL components of the "guide".
            */
            'fallback' => true,
        ],
    ],

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
        | overflow: avoids hiding the overflow, allowing the scroll of the page.
        | blur: enables the background blur effect by default.
        | persistent: enables the dialog to not be closed by clicking outside by default.
        */
        'dialog' => [
            'z-index' => 'z-50',
            'overflow' => false,
            'blur' => false,
            'persistent' => false,
        ],

        /*
        |----------------------------------------------------------------------
        | Form
        |----------------------------------------------------------------------
        */
        'form' => [
            /*
            |----------------------------------------------------------------------
            | Color
            |----------------------------------------------------------------------
            | colors: array of custom colors to be used in the color picker.
            */
            'color' => [
                'colors' => null,
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Modal
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | overflow: avoids hiding the overflow, allowing the scroll of the page.
        | blur: enables the background blur effect by default (Allowed: false, sm, md, lg, xl).
        | persistent: enables the modal to not be closed by clicking outside by default.
        | size: controls the default modal size (Allowed: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl).
        | center: controls if the modal is centered by default.
        */
        'modal' => [
            'z-index' => 'z-50',
            'overflow' => false,
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
        | overflow: avoids hiding the overflow, allowing the scroll of the page.
        | blur: enables the background blur effect by default.
        | opacity: enables the background opacity by default.
        */
        'loading' => [
            'z-index' => 'z-50',
            'overflow' => false,
            'blur' => false,
            'opacity' => true,
        ],
        /*
        |----------------------------------------------------------------------
        | Slide
        |----------------------------------------------------------------------
        |
        | z-index: controls the default z-index.
        | overflow: avoids hiding the overflow, allowing the scroll of the page.
        | blur: enables the background blur effect by default (Allowed: false, sm, md, lg, xl).
        | persistent: enables the slide to not be closed by clicking outside by default.
        | size: controls the default modal size (Allowed: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full).
        | position: controls the default slide position (Allowed: right, left, top, bottom).
        */
        'slide' => [
            'z-index' => 'z-50',
            'overflow' => false,
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
        | timeout: controls the default timeout in seconds.
        */
        'toast' => [
            'z-index' => 'z-50',
            'progress' => true,
            'expandable' => false,
            'position' => 'top-right',
            'timeout' => 3,
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
        'boolean' => Components\Boolean::class,
        'button' => Components\Button\Button::class,
        'button.circle' => Components\Button\Circle::class,
        'card' => Components\Card::class,
        'checkbox' => Components\Form\Checkbox::class,
        'color' => Components\Form\Color::class,
        'clipboard' => Components\Clipboard::class,
        'date' => Components\Form\Date::class,
        'dialog' => Components\Interaction\Dialog::class,
        'dropdown' => Components\Dropdown\Dropdown::class,
        'dropdown.items' => Components\Dropdown\Items::class,
        'environment' => Components\Environment::class,
        'error' => Components\Form\Error::class,
        'errors' => Components\Errors::class,
        'floating' => Components\Floating::class,
        'upload' => Components\Form\Upload::class,
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
        'progress' => Components\Progress\Progress::class,
        'progress.circle' => Components\Progress\Circle::class,
        'radio' => Components\Form\Radio::class,
        'range' => Components\Form\Range::class,
        'rating' => Components\Rating::class,
        'select.native' => Components\Select\Native::class,
        'select.styled' => Components\Select\Styled::class,
        'signature' => Components\Signature::class,
        'skeleton' => Components\Skeleton::class,
        'slide' => Components\Slide::class,
        'stats' => Components\Stats::class,
        'step' => Components\Step\Step::class,
        'step.items' => Components\Step\Items::class,
        'tab' => Components\Tab\Tab::class,
        'tag' => Components\Form\Tag::class,
        'table' => Components\Table::class,
        'tab.items' => Components\Tab\Items::class,
        'textarea' => Components\Form\Textarea::class,
        'theme-switch' => Components\ThemeSwitch::class,
        'time' => Components\Form\Time::class,
        'toast' => Components\Interaction\Toast::class,
        'toggle' => Components\Form\Toggle::class,
        'tooltip' => Components\Tooltip::class,
        'reaction' => Components\Reaction::class,
        'wrapper.input' => Components\Wrapper\Input::class,
        'wrapper.radio' => Components\Wrapper\Radio::class,
    ],
];
