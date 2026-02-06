<?php

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
    | For example, prefixing as 'ts-', the `alert` usage will be: '<x-ts-alert />'
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
    | set this value as "true," you will use "invalidate" of all form components
    | globally, without the need to specific it individually per component.
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
            // \TallStackUi\Components\Alert\Component::class,
            // \TallStackUi\Components\Avatar\Component::class
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
        'alert' => \TallStackUi\Components\Alert\Component::class,
        'avatar' => \TallStackUi\Components\Avatar\Component::class,
        'badge' => \TallStackUi\Components\Badge\Component::class,
        'banner' => \TallStackUi\Components\Banner\Component::class,
        'boolean' => \TallStackUi\Components\Boolean\Component::class,
        'button' => \TallStackUi\Components\Button\Normal\Component::class,
        'button.circle' => \TallStackUi\Components\Button\Circle\Component::class,
        'carousel' => \TallStackUi\Components\Carousel\Component::class,
        'card' => \TallStackUi\Components\Card\Component::class,
        'checkbox' => \TallStackUi\Components\Form\Checkbox\Component::class,
        'color' => [
            \TallStackUi\Components\Form\Color\Component::class,
            /*
            |----------------------------------------------------------------------
            | Color Settings
            |----------------------------------------------------------------------
            | custom: array of custom colors to be used in the color picker.
            */
            [
                'custom' => [],
            ],
        ],
        'clipboard' => \TallStackUi\Components\Clipboard\Component::class,
        'currency' => \TallStackUi\Components\Form\Currency\Component::class,
        'date' => \TallStackUi\Components\Form\Date\Component::class,
        'dialog' => [
            \TallStackUi\Components\Dialog\Component::class,
            /*
            |----------------------------------------------------------------------
            | Dialog Settings
            |----------------------------------------------------------------------
            | z-index: controls the default z-index.
            | overflow: avoids hiding the overflow, allowing the scroll of the page.
            | blur: enables the background blur effect by default.
            | persistent: enables the dialog to not be closed by clicking outside by default.
            */
            [
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
            ],
        ],
        'dropdown' => \TallStackUi\Components\Dropdown\Main\Component::class,
        'dropdown.items' => \TallStackUi\Components\Dropdown\Items\Component::class,
        'dropdown.submenu' => \TallStackUi\Components\Dropdown\Submenu\Component::class,
        'environment' => \TallStackUi\Components\Environment\Component::class,
        'error' => \TallStackUi\Components\Form\Error\Component::class,
        'errors' => \TallStackUi\Components\Errors\Component::class,
        'floating' => \TallStackUi\Components\Floating\Component::class,
        'hint' => \TallStackUi\Components\Form\Hint\Component::class,
        'icon' => [
            \TallStackUi\Components\Icon\Component::class,
            [
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
                    | icons via BladeUI, you can optionally change the internal icons to custom
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
        ],
        'input' => \TallStackUi\Components\Form\Input\Component::class,
        'label' => \TallStackUi\Components\Form\Label\Component::class,
        'layout' => [
            \TallStackUi\Components\Layout\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Layout Global Settings
            |----------------------------------------------------------------------
            |
            | ignore: Controls the registration of the layout component and all its children,
            | useful for situations where you want to ignore these components in favor
            | of avoiding conflict with your layout component.
            */
            [
                'ignore' => env('TALLSTACKUI_IGNORE_LAYOUT_REGISTRATION', false),
            ],
        ],
        'layout.header' => \TallStackUi\Components\Layout\Header\Component::class,
        'link' => \TallStackUi\Components\Link\Component::class,
        'loading' => [
            \TallStackUi\Components\Loading\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Loading Global Settings
                |----------------------------------------------------------------------
                |
                | z-index: controls the default z-index.
                | overflow: avoids hiding the overflow, allowing the scroll of the page.
                | blur: enables the background blur effect by default.
                | opacity: enables the background opacity by default.
                */
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'opacity' => true,
            ],
        ],
        'key-value' => \TallStackUi\Components\KeyValue\Component::class,
        'modal' => [
            \TallStackUi\Components\Modal\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Modal Global Settings
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
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
                'size' => '2xl',
                'center' => false,
                'scrollable' => false,
                'scrollbar' => 'thin',
            ],
        ],
        'number' => \TallStackUi\Components\Form\Number\Component::class,
        'password' => [
            \TallStackUi\Components\Form\Password\Component::class,
            /*
            |----------------------------------------------------------------------
            | Password Global Settings
            |----------------------------------------------------------------------
            | rules: array of default rules for the password generator.
            */
            [
                'rules' => [
                    'min' => '8',
                    'mixed' => true,
                    'numbers' => true,
                    'symbols' => '!@#$%^&*()_+-=',
                ],
            ],
        ],
        'pin' => \TallStackUi\Components\Form\Pin\Component::class,
        'progress' => \TallStackUi\Components\Progress\Bar\Component::class,
        'progress.circle' => \TallStackUi\Components\Progress\Circle\Component::class,
        'radio' => \TallStackUi\Components\Form\Radio\Component::class,
        'range' => \TallStackUi\Components\Form\Range\Component::class,
        'rating' => \TallStackUi\Components\Rating\Component::class,
        'side-bar' => \TallStackUi\Components\SideBar\Main\Component::class,
        'side-bar.item' => \TallStackUi\Components\SideBar\Item\Component::class,
        'side-bar.separator' => \TallStackUi\Components\SideBar\Separator\Component::class,
        'select.native' => \TallStackUi\Components\Form\Select\Native\Component::class,
        'select.styled' => [
            \TallStackUi\Components\Form\Select\Styled\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Select Styled Global Settings
                |----------------------------------------------------------------------
                | unfiltered: allow all select API-styled components to be unfiltered by default.
                */
                'unfiltered' => false,
            ],
        ],
        'signature' => \TallStackUi\Components\Signature\Component::class,
        'slide' => [
            \TallStackUi\Components\Slide\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Slide Global Settings
                |----------------------------------------------------------------------
                |
                | z-index: controls the default z-index.
                | overflow: avoids hiding the overflow, allowing the scroll of the page.
                | blur: enables the background blur effect by default (Allowed: false, sm, md, lg, xl).
                | persistent: enables the slide to not be closed by clicking outside by default.
                | size: controls the default modal size (Allowed: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full).
                | position: controls the default slide position (Allowed: right, left, top, bottom).
                */
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
                'size' => 'lg',
                'position' => 'right',
            ],
        ],
        'stats' => \TallStackUi\Components\Stats\Component::class,
        'step' => \TallStackUi\Components\Step\Main\Component::class,
        'step.items' => \TallStackUi\Components\Step\Items\Component::class,
        'tab' => \TallStackUi\Components\Tab\Main\Component::class,
        'tag' => \TallStackUi\Components\Form\Tag\Component::class,
        'table' => \TallStackUi\Components\Table\Component::class,
        'tab.items' => \TallStackUi\Components\Tab\Items\Component::class,
        'textarea' => \TallStackUi\Components\Form\Textarea\Component::class,
        'theme-switch' => \TallStackUi\Components\ThemeSwitch\Component::class,
        'time' => \TallStackUi\Components\Form\Time\Component::class,
        'toast' => [
            \TallStackUi\Components\Toast\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Toast Global Settings
                |----------------------------------------------------------------------
                |
                | z-index: controls the default z-index.
                | progress: enables the progress bar.
                | expandable: enables the expanded effect by default.
                | position: controls the default toast position (Allowed: top-right, top-left, bottom-right, bottom-left).
                | timeout: controls the default timeout in seconds.
                */
                'z-index' => 'z-50',
                'progress' => true,
                'expandable' => false,
                'position' => 'top-right',
                'timeout' => 3,
            ],
        ],
        'toggle' => \TallStackUi\Components\Form\Toggle\Component::class,
        'tooltip' => \TallStackUi\Components\Tooltip\Component::class,
        'upload' => \TallStackUi\Components\Form\Upload\Component::class,
        'reaction' => \TallStackUi\Components\Reaction\Component::class,
        'wrapper.input' => \TallStackUi\Components\Wrapper\Input\Component::class,
        'wrapper.radio' => \TallStackUi\Components\Wrapper\Radio\Component::class,
    ],
];
