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
    | Invalidate Components
    |--------------------------------------------------------------------------
    |
    | Controls the "invalidation" of all form components globally. The "invalidate"
    | is the way to prevent showing validation errors in the components. When you
    | set this value as "true" you will use "invalidate" of all form components
    | globally, without need to specific it individually per component.
    */
    'invalidate_global' => false,

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
            // Components\Avatar::class
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
        | Allowed: heroicons or BladeUI (check the docs).
        */
        'type' => env('TALLSTACKUI_ICON_TYPE', 'heroicons'),

        /*
        |----------------------------------
        | Default and in-use icon style.
        |----------------------------------
        | Allowed: solid, outline (Heroicons only).
        */
        'style' => env('TALLSTACKUI_ICON_STYLE', 'solid'),

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
            | icons via BladeUi you can optionally change the internal icons to custom
            | icons, causing this to reflect new icon looks for the internal components.
            */
            'guide' => [
                'arrow-path' => null,
                'arrow-trending-up' => null,
                'arrow-trending-down' => null,
                'arrow-up-tray' => null,
                'bars-4' => null,
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

            /*
            |----------------------------------------------------------------------
            | Password
            |----------------------------------------------------------------------
            | rules: array of default rules for the password generator.
            */
            'password' => [
                'rules' => [
                    'min' => '8',
                    'mixed' => true,
                    'numbers' => true,
                    'symbols' => '!@#$%^&*()_+-=',
                ],
            ],

            /*
            |----------------------------------------------------------------------
            | Select Styled
            |----------------------------------------------------------------------
            | unfiltered: allow all select API styled components to be unfiltered by default.
            */
            'select' => [
                'styled' => [
                    'unfiltered' => false,
                ],
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
        | scrollable: when enabled, fixes the title and footer while allowing only the body content to scroll.
        | scrollbar: controls the type of scrollbar (Allowed: null, thin, thick),
        */
        'modal' => [
            'z-index' => 'z-50',
            'overflow' => false,
            'blur' => false,
            'persistent' => false,
            'size' => '2xl',
            'center' => false,
            'scrollable' => false,
            'scrollbar' => 'thin',
        ],

        /*
        |----------------------------------------------------------------------
        | Layout
        |----------------------------------------------------------------------
        |
        | Controls the registration of the layout component and all its children,
        | useful for situations where you want to ignore these components in favor
        | of avoiding conflict with your layout component.
        */
        'layout' => [
            'avoid' => env('TALLSTACKUI_AVOID_LAYOUT_REGISTRATION', false),
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
        | expandable: enables the expanded effect by default.
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
        'carousel' => Components\Carousel::class,
        'card' => Components\Card::class,
        'checkbox' => Components\Form\Checkbox::class,
        'color' => Components\Form\Color::class,
        'clipboard' => Components\Clipboard::class,
        'currency' => Components\Form\Currency::class,
        'date' => Components\Form\Date::class,
        'dialog' => Components\Interaction\Dialog::class,
        'dropdown' => Components\Dropdown\Dropdown::class,
        'dropdown.items' => Components\Dropdown\Items::class,
        'dropdown.submenu' => Components\Dropdown\Submenu::class,
        'environment' => Components\Environment::class,
        'error' => Components\Form\Error::class,
        'errors' => Components\Errors::class,
        'floating' => Components\Floating::class,
        'hint' => Components\Form\Hint::class,
        'icon' => Components\Icon::class,
        'input' => Components\Form\Input::class,
        'label' => Components\Form\Label::class,
        'layout' => Components\Layout\Layout::class,
        'layout.header' => Components\Layout\Header::class,
        'link' => Components\Link::class,
        'loading' => Components\Loading::class,
        'key-value' => Components\KeyValue::class,
        'modal' => Components\Modal::class,
        'number' => Components\Form\Number::class,
        'password' => Components\Form\Password::class,
        'pin' => Components\Form\Pin::class,
        'progress' => Components\Progress\Progress::class,
        'progress.circle' => Components\Progress\Circle::class,
        'radio' => Components\Form\Radio::class,
        'range' => Components\Form\Range::class,
        'rating' => Components\Rating::class,
        'side-bar' => Components\Layout\SideBar\SideBar::class,
        'side-bar.item' => Components\Layout\SideBar\Item::class,
        'side-bar.separator' => Components\Layout\SideBar\Separator::class,
        'select.native' => Components\Form\Select\Native::class,
        'select.styled' => Components\Form\Select\Styled::class,
        'signature' => Components\Signature::class,
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
        'upload' => Components\Form\Upload::class,
        'reaction' => Components\Reaction::class,
        'wrapper.input' => Components\Wrapper\Input::class,
        'wrapper.radio' => Components\Wrapper\Radio::class,
    ],
];
