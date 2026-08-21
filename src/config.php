<?php

use TallStackUi\Components;

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
    |
    */
    'prefix' => env('TALLSTACKUI_PREFIX'),

    /*
    |--------------------------------------------------------------------------
    | Color Classes Namespace
    |--------------------------------------------------------------------------
    |
    | The namespace related to classes used for component color personalization.
    |
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
    |
    */
    'invalidate_global' => env('TALLSTACKUI_INVALIDATE_GLOBAL', false),

    /*
    |--------------------------------------------------------------------------
    | Floating Scroll Lock
    |--------------------------------------------------------------------------
    |
    | Controls whether the page scroll is locked while any component built on
    | top of the floating component is open, the same way modals and slides
    | already lock it. This affects every floating consumer at once: dropdowns
    | (including submenus), autocomplete, color, date, time, password, select
    | styled, upload, calendar and list menus.
    |
    | Nested and stacked floatings are reference counted, so the lock is taken
    | by the first one to open and released only by the last one to close.
    |
    */
    'floating_scroll_lock' => env('TALLSTACKUI_FLOATING_SCROLL_LOCK', false),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Control the debug mode for TallStackUI components.
    |
    */
    'debug' => [
        'status' => env('TALLSTACKUI_DEBUG_MODE', false),

        /*
        |----------------------------------------------------------------------
        | Controls the environments where the debug mode can be enabled.
        |----------------------------------------------------------------------
        |
        */
        'environments' => array_map('trim', explode(',', env('TALLSTACKUI_DEBUG_ENVIRONMENTS', 'local,sandbox,staging'))),

        /*
        |----------------------------------------------------------------------
        | Ignore debug mode for specific components.
        |----------------------------------------------------------------------
        |
        */
        'ignore' => [
            // Components\Alert\Component::class,
            // Components\Avatar\Component::class
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Component List
    |--------------------------------------------------------------------------
    |
    | List of all TallStackUI components and their possible settings.
    |
    */
    'components' => [
        'accordion' => [
            Components\Accordion\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Accordion Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
            ],
        ],
        'accordion.items' => Components\Accordion\Items\Component::class,
        'alert' => [
            Components\Alert\Component::class,
            /*
            |----------------------------------------------------------------------
            | Alert Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the side border by default, following the same
            | "side" or "side:color" syntax of the inline prop (Allowed sides: left, right).
            |
            */
            [
                'shadowless' => false,
                'bordered' => null,
            ],
        ],
        'autocomplete' => [
            Components\Form\Autocomplete\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Autocomplete Global Settings
                |----------------------------------------------------------------------
                | strict: when true, all autocomplete components will, by default, only
                | accept values that exist in their items list. The wire:model is only
                | updated when a row is picked from the dropdown, and the input reverts
                | to the last selected value on blur with an unmatched query.
                | select: the default field mapping of the items (e.g., 'value:name|description:email|image:avatar').
                | indicator: the default loading indicator of the remote results. Null keeps the original icon.
                | Use "spinner" or "spinner.{type}" to render a Spinner instead (Allowed types: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave, shimmer, caret, terminal, thinking).
                |
                */
                'strict' => false,
                'select' => null,
                'indicator' => null,
            ],
        ],
        'back-to-top' => [
            Components\BackToTop\Component::class,
            /*
            |----------------------------------------------------------------------
            | Back To Top Global Settings
            |----------------------------------------------------------------------
            | immediate: jumps to the top instead of scrolling smoothly.
            | square: rounds the button as a square instead of a circle.
            | color: controls the button color (Allowed: any palette key).
            | icon: controls the icon rendered inside the button.
            | position: controls the corner the button sits on (Allowed: bottom-left, bottom-right).
            | size: controls the button size (Allowed: xs, sm, md, lg).
            |
            */
            [
                'immediate' => false,
                'square' => false,
                'color' => 'primary',
                'icon' => 'chevron-up',
                'position' => 'bottom-right',
                'size' => 'md',
            ],
        ],
        'avatar' => [
            Components\Avatar\Component::class,
            /*
            |----------------------------------------------------------------------
            | Avatar Global Settings
            |----------------------------------------------------------------------
            | size: controls the avatar size (Allowed: xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl).
            | borderless: removes the border by default.
            | gravatar.default: the image Gravatar serves when the email has no account. (Allowed: 404, mp, identicon, monsterid, wavatar, retro, robohash, blank).
            | gravatar.rating: the highest rating Gravatar is allowed to serve (Allowed: g, pg, r, x).
            |
            */
            [
                'size' => 'md',
                'borderless' => false,
                'gravatar' => [
                    'default' => 'mp',
                    'rating' => 'g',
                ],
            ],
        ],
        'avatar.group' => Components\Avatar\Group\Component::class,
        'badge' => [
            Components\Badge\Component::class,
            /*
            |----------------------------------------------------------------------
            | Badge Global Settings
            |----------------------------------------------------------------------
            |
            | round: the default corner shape. False keeps rounded-md.
            |
            */
            [
                'round' => false,
            ],
        ],
        'banner' => Components\Banner\Component::class,
        'breadcrumbs' => [
            Components\Breadcrumbs\Component::class,
            /*
            |----------------------------------------------------------------------
            | Breadcrumbs Settings
            |----------------------------------------------------------------------
            |
            | files: array of files (relative to base_path()) that register breadcrumb definitions.
            |
            */
            [
                'files' => [
                    'routes/breadcrumbs.php',
                ],
            ],
        ],
        'boolean' => Components\Boolean\Component::class,
        'button' => [
            Components\Button\Normal\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Button Global Settings
                |----------------------------------------------------------------------
                |
                | round: the default corner shape. False keeps rounded-md, true is the pill. (Allowed: xs, sm, md, lg, xl, full).
                | spinner: controls the default wire:loading spinner variant. (Allowed: null, ring, throbber, gradient, ping, dots, pulse, typing, bars, wave).
                |
                */
                'round' => false,
                'spinner' => null,
            ],
        ],
        'button.circle' => Components\Button\Circle\Component::class,
        'button.group' => Components\Button\Group\Component::class,
        'calendar' => [
            Components\Calendar\Component::class,
            /*
            |----------------------------------------------------------------------
            | Calendar Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            | start: the first day of the week (0 = Sunday ... 6 = Saturday).
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
                'start' => 0,
            ],
        ],
        'carousel' => Components\Carousel\Component::class,
        'card' => [
            Components\Card\Component::class,
            /*
            |----------------------------------------------------------------------
            | Card Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            | round: the default corner shape. False keeps rounded-lg (Allowed: xs, sm, md, lg, xl, 2xl).
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
                'round' => false,
            ],
        ],
        'chart' => [
            Components\Chart\Component::class,
            /*
            |----------------------------------------------------------------------
            | Chart Settings
            |----------------------------------------------------------------------
            | height: default rendered height, in pixels.
            | grid: default gridlines and labelled vertical axis.
            | legend: default series names with a clickable color swatch.
            | tooltip: default crosshair and tooltip following the pointer.
            | markers: default dot on every plotted point.
            | fit: how the horizontal axis labels avoid overlapping on narrow plots: thin, rotate or stagger.
            | curve: how a line joins its points: smooth, straight or step.
            | round: corner radius of the bars: none, sm, md or lg.
            | corners: which corners of a bar round: all, or only the end away from the axis.
            |
            */
            [
                'height' => 240,
                'grid' => false,
                'legend' => false,
                'tooltip' => false,
                'markers' => false,
                'fit' => 'thin',
                'curve' => 'smooth',
                'round' => 'sm',
                'corners' => 'all',
            ],
        ],
        'checkbox' => Components\Form\Checkbox\Component::class,
        'checkbox.group' => [
            Components\Form\Checkbox\Group\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Checkbox Group Global Settings
                |----------------------------------------------------------------------
                | select: the default field mapping of the options (e.g., 'label:name|value:id|description:email').
                |
                */
                'select' => null,
            ],
        ],
        'color' => [
            Components\Form\Color\Component::class,
            /*
            |----------------------------------------------------------------------
            | Color Settings
            |----------------------------------------------------------------------
            | colors: array of custom colors to be used in the color picker.
            | picker: enables the full Tailwind CSS palette picker by default.
            | selectable: blocks typing so colors can only be picked by default.
            | clearable: displays the clear button by default.
            |
            */
            [
                'colors' => [],
                'picker' => false,
                'selectable' => false,
                'clearable' => false,
            ],
        ],
        'clipboard' => Components\Clipboard\Component::class,
        'command-palette' => [
            Components\CommandPalette\Component::class,
            /*
            |----------------------------------------------------------------------
            | Command Palette Settings
            |----------------------------------------------------------------------
            |
            | actionable: the callable class for handling item selection (e.g., App\Support\GlobalSearch::class).
            | request: the data source for the command palette.
            | select: the default field mapping of the results (e.g., 'label:name|value:id|description:email|image:avatar').
            | z-index: controls the default z-index.
            | blur: enables the background blur effect (Allowed: false, sm, md, lg, xl).
            | overflow: avoids hiding the overflow, allowing the scroll of the page.
            | shortcut: keyboard shortcut to toggle the palette (e.g., 'ctrl.k', 'ctrl.shift.p').
            | recycle: when true, preserves previous results when reopening the palette.
            | elements: when true, shows the keyboard hints in the footer.
            | scrollbar: when true, applies a custom minimal scrollbar to the results list.
            | centered: when true, centers the palette vertically on mobile with fully rounded corners.
            | overlay: when false, hides the dimmed background overlay rendered behind the palette.
            |
            */
            [
                'actionable' => null,
                'request' => null,
                'select' => null,
                'z-index' => 'z-50',
                'blur' => false,
                'overflow' => false,
                'shortcut' => 'ctrl.k',
                'recycle' => true,
                'elements' => true,
                'scrollbar' => true,
                'centered' => false,
                'overlay' => true,
            ],
        ],
        'currency' => [
            Components\Form\Currency\Component::class,
            /*
            |----------------------------------------------------------------------
            | Currency Global Settings
            |----------------------------------------------------------------------
            |
            | mutate: when true, every currency component defaults to sending the
            | formatted display string (e.g. "2,000.00") to the Livewire property.
            | decimal: when true, every currency component defaults to sending the
            | parsed decimal string (e.g. "2000.00") to the Livewire property.
            | The two are mutually exclusive — setting both raises a validation
            | exception at render time.
            |
            */
            [
                'mutate' => false,
                'decimal' => false,
            ],
        ],
        'date' => [
            Components\Form\Date\Component::class,
            /*
            |----------------------------------------------------------------------
            | Date Global Settings
            |----------------------------------------------------------------------
            | start: the first day of the week (0 = Sunday ... 6 = Saturday).
            |
            */
            [
                'start' => 0,
            ],
        ],
        'dialog' => [
            Components\Dialog\Component::class,
            /*
            |----------------------------------------------------------------------
            | Dialog Settings
            |----------------------------------------------------------------------
            | z-index: controls the default z-index.
            | overflow: avoids hiding the overflow, allowing the scroll of the page.
            | blur: enables the background blur effect by default.
            | persistent: enables the dialog to not be closed by clicking outside by default.
            |
            */
            [
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
            ],
        ],
        'dial' => Components\Dial\Main\Component::class,
        'dial.items' => Components\Dial\Items\Component::class,
        'dropdown' => Components\Dropdown\Main\Component::class,
        'dropdown.items' => Components\Dropdown\Items\Component::class,
        'dropdown.submenu' => Components\Dropdown\Submenu\Component::class,
        'editor' => [
            Components\Editor\Component::class,
            /*
            |----------------------------------------------------------------------
            | Editor Settings
            |----------------------------------------------------------------------
            | markdown: stores the content as Markdown instead of HTML.
            | output_classes: stamps a class on every element the editor writes.
            | output_classes_prefix: the namespace of those names, ending with a dash. Null keeps "tsui-editor-".
            | toolbar: the canonical buttons and the order they are rendered in.
            | counters: displays the word and line counters in the footer.
            | min_height, max_height: the editable boundaries, in any CSS unit.
            | upload: the constraints checked in the browser before uploading.
            | sanitization: the whitelist applied to any pasted content.
            |
            */
            [
                'markdown' => false,
                'output_classes' => false,
                'output_classes_prefix' => null,
                'toolbar' => [
                    'style', 'blockquote',
                    'bold', 'italic', 'underline', 'strikethrough',
                    'ordered-list', 'unordered-list', 'indent', 'outdent',
                    'align',
                    'code', 'code-block', 'clear-format',
                    'link', 'image', 'hr',
                    'undo', 'redo',
                    'fullscreen',
                ],
                'counters' => true,
                'min_height' => '12rem',
                'max_height' => '40rem',
                'upload' => [
                    'mimes' => ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
                    'max_size' => 5120,
                ],
                'sanitization' => [
                    'allowed_tags' => [
                        'p', 'br', 'strong', 'em', 'u', 's', 'code', 'pre',
                        'h1', 'h2', 'h3', 'h4', 'h5',
                        'ul', 'ol', 'li',
                        'blockquote', 'hr',
                        'a', 'img', 'span', 'div',
                    ],
                    'allowed_attributes' => [
                        'a' => ['href', 'target', 'rel'],
                        'img' => ['src', 'alt', 'width', 'height'],
                        'span' => ['style'],
                        'div' => ['style'],
                        'p' => ['style'],
                        'h1' => ['style'],
                        'h2' => ['style'],
                        'h3' => ['style'],
                        'h4' => ['style'],
                        'h5' => ['style'],
                        'li' => ['style'],
                    ],
                    'allowed_styles' => ['font-size', 'text-align', 'margin-left'],
                ],
            ],
        ],
        'environment' => Components\Environment\Component::class,
        'error' => Components\Form\Error\Component::class,
        'errors' => [
            Components\Errors\Component::class,
            /*
            |----------------------------------------------------------------------
            | Errors Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            | paddingless: removes the horizontal padding of the wrapper by default.
            | numeric: renders the error list as an ordered list by default.
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
                'paddingless' => false,
                'numeric' => false,
            ],
        ],
        'floating' => Components\Floating\Component::class,
        'gallery' => [
            Components\Gallery\Component::class,
            /*
            |----------------------------------------------------------------------
            | Gallery Settings
            |----------------------------------------------------------------------
            | columns: default column count (2-6) for the grid and masonry layouts.
            | ratio: default tile shape: square, video or portrait.
            | limit: default number of tiles rendered by the feature layout.
            |
            */
            [
                'columns' => 3,
                'ratio' => null,
                'limit' => 7,
            ],
        ],
        'hint' => Components\Form\Hint\Component::class,
        'icon' => [
            Components\Icon\Component::class,
            [
                /*
                |----------------------------------
                | Default and in-use icon type.
                |----------------------------------
                | Allowed: heroicons, BladeUI or anonymous Blade components as svg (check the docs).
                |
                */
                'type' => env('TALLSTACKUI_ICON_TYPE', 'heroicons'),

                /*
                |----------------------------------
                | Default and in-use icon style.
                |----------------------------------
                | Allowed: solid, outline (Heroicons only).
                |
                */
                'style' => env('TALLSTACKUI_ICON_STYLE', 'solid'),

                /*
                |----------------------------------
                | Default and in-use icon size.
                |----------------------------------
                | Allowed: xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl.
                | Ignored when the icon declares its own [class].
                |
                */
                'size' => 'md',

                /*
                |----------------------------------
                | Custom icon configuration.
                |----------------------------------
                |
                */
                'custom' => [
                    /*
                    |----------------------------------
                    | Custom icons guide.
                    |----------------------------------
                    |
                    | These icons are used internally in the components. When using custom
                    | icons via BladeUI or local SVG files, you can change the internal icons
                    | to custom icons, causing this to reflect new icon looks for the internal
                    | components. For local SVGs, map keys to your SVG filenames. If null,
                    | uses the key as filename (e.g., 'check-circle' → check-circle.blade.php).
                    |
                    */
                    'guide' => [
                        'arrow-path' => null,
                        'arrow-trending-up' => null,
                        'arrow-trending-down' => null,
                        'arrow-up-tray' => null,
                        'arrow-uturn-left' => null,
                        'arrow-uturn-right' => null,
                        'arrows-pointing-in' => null,
                        'arrows-pointing-out' => null,
                        'backspace' => null,
                        'bars-3-bottom-left' => null,
                        'bars-4' => null,
                        'calendar' => null,
                        'check' => null,
                        'check-circle' => null,
                        'chevron-double-left' => null,
                        'chevron-double-right' => null,
                        'chevron-down' => null,
                        'chevron-left' => null,
                        'chevron-right' => null,
                        'chevron-up' => null,
                        'chevron-up-down' => null,
                        'clipboard' => null,
                        'clipboard-document' => null,
                        'cloud-arrow-up' => null,
                        'clock' => null,
                        'code-bracket' => null,
                        'code-bracket-square' => null,
                        'document-check' => null,
                        'document-text' => null,
                        'exclamation-circle' => null,
                        'eye' => null,
                        'eye-slash' => null,
                        'information-circle' => null,
                        'link' => null,
                        'list-bullet' => null,
                        'magnifying-glass' => null,
                        'minus' => null,
                        'moon' => null,
                        'numbered-list' => null,
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
        'input' => Components\Form\Input\Component::class,
        'input.select' => Components\Form\InputSelect\Component::class,
        'label' => Components\Form\Label\Component::class,
        'layout' => [
            Components\Layout\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Layout Global Settings
            |----------------------------------------------------------------------
            |
            | ignore: Controls the registration of the layout component and all its children,
            | useful for situations where you want to ignore these components in favor
            | of avoiding conflict with your layout component.
            |
            */
            [
                'ignore' => env('TALLSTACKUI_IGNORE_LAYOUT_REGISTRATION', false),
            ],
        ],
        'layout.header' => [
            Components\Layout\Header\Component::class,
            /*
            |----------------------------------------------------------------------
            | Layout Header Global Settings
            |----------------------------------------------------------------------
            |
            | size: controls the header height (Allowed: sm, md, lg, xl).
            |
            */
            [
                'size' => 'md',
            ],
        ],
        'link' => [
            Components\Link\Component::class,
            /*
            |----------------------------------------------------------------------
            | Link Global Settings
            |----------------------------------------------------------------------
            | navigate: adds wire:navigate to every link by default.
            | navigate-hover: adds wire:navigate.hover to every link by default.
            |
            | The two are mutually exclusive, so declaring either one of them
            | inline suppresses the global default of both.
            |
            */
            [
                'navigate' => false,
                'navigate-hover' => false,
            ],
        ],
        'list' => Components\List\Main\Component::class,
        'list.items' => Components\List\Items\Component::class,
        'loading' => [
            Components\Loading\Component::class,
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
                | indicator: the default overlay indicator. Null keeps the original.
                | Use "spinner" or "spinner.{type}" to render a Spinner instead
                | (Allowed types: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave, shimmer, caret, terminal, thinking).
                |
                */
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'opacity' => true,
                'indicator' => null,
            ],
        ],
        'kbd' => [
            Components\Kbd\Component::class,
            /*
            |----------------------------------------------------------------------
            | Kbd Global Settings
            |----------------------------------------------------------------------
            | borderless: removes the border by default.
            | shadowless: removes the shadow by default.
            |
            */
            [
                'borderless' => false,
                'shadowless' => false,
            ],
        ],
        'key-value' => Components\KeyValue\Component::class,
        'modal' => [
            Components\Modal\Component::class,
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
                | center: controls if the modal is centered by default. A breakpoint centers only from
                | it upwards, behaving as not centered below (Allowed: true, false, sm, md, lg, xl, 2xl).
                | scrollable: when enabled, fixes the title and footer while allowing only the body content to scroll.
                | scrollbar: controls the type of scrollbar (Allowed: null, thin, thick),
                | handle: displays a grabber on mobile allowing the modal to be dragged down to close.
                |
                */
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
                'size' => '2xl',
                'center' => false,
                'scrollable' => false,
                'scrollbar' => 'thin',
                'handle' => false,
            ],
        ],
        'number' => [
            Components\Form\Number\Component::class,
            /*
            |----------------------------------------------------------------------
            | Number Global Settings
            |----------------------------------------------------------------------
            | centralized: centers the value between the control buttons by default.
            | selectable: blocks typing so the value only changes through the buttons.
            | delay: controls the press-and-hold repeat interval (delay * 100ms).
            | chevron: replaces the plus/minus icons with chevrons by default.
            |
            */
            [
                'centralized' => false,
                'selectable' => false,
                'delay' => 2,
                'chevron' => false,
            ],
        ],
        'password' => [
            Components\Form\Password\Component::class,
            /*
            |----------------------------------------------------------------------
            | Password Global Settings
            |----------------------------------------------------------------------
            | rules: array of default rules for the password generator.
            |
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
        'pin' => Components\Form\Pin\Component::class,
        'progress' => Components\Progress\Bar\Component::class,
        'progress.circle' => Components\Progress\Circle\Component::class,
        'qr-code' => [
            Components\QrCode\Component::class,
            /*
            |----------------------------------------------------------------------
            | QrCode Global Settings
            |----------------------------------------------------------------------
            | size: default rendered size (Allowed: xs, sm, md, lg, xl, 2xl).
            | pixels: width, in pixels, of the image produced by copy and download.
            |
            */
            [
                'size' => 'md',
                'pixels' => 1024,
            ],
        ],
        'radio' => Components\Form\Radio\Component::class,
        'radio.group' => [
            Components\Form\Radio\Group\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Radio Group Global Settings
                |----------------------------------------------------------------------
                | select: the default field mapping of the options (e.g., 'label:name|value:id|description:email').
                |
                */
                'select' => null,
            ],
        ],
        'range' => Components\Form\Range\Component::class,
        'rating' => Components\Rating\Component::class,
        'side-bar' => [
            Components\Layout\SideBar\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Side Bar Global Settings
            |----------------------------------------------------------------------
            | smart: marks the item matching the current route as active by default.
            | collapsible: renders the side bar as a collapsible rail by default.
            | thin-scroll: applies the soft scrollbar to the items list by default.
            | thick-scroll: applies the custom scrollbar to the items list by default.
            | navigate: adds wire:navigate to every item by default.
            | navigate-hover: adds wire:navigate.hover to every item by default.
            */
            [
                'smart' => false,
                'collapsible' => false,
                'thin-scroll' => false,
                'thick-scroll' => false,
                'navigate' => false,
                'navigate-hover' => false,
            ],
        ],
        'side-bar.item' => Components\Layout\SideBar\Item\Component::class,
        'side-bar.separator' => Components\Layout\SideBar\Separator\Component::class,
        'select.native' => [
            Components\Form\Select\Native\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Select Native Global Settings
                |----------------------------------------------------------------------
                | select: the default field mapping of the options (e.g., 'label:name|value:id').
                |
                */
                'select' => null,
            ],
        ],
        'select.styled' => [
            Components\Form\Select\Styled\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Select Styled Global Settings
                |----------------------------------------------------------------------
                | unfiltered: allow all select API-styled components to be unfiltered by default.
                | recycle: when true, preserves previous results when reopening the select.
                | select: the default field mapping of the options (e.g., 'label:name|value:id|description:email|image:avatar').
                | indicator: the default loading indicator of the remote results. Null keeps the original icon.
                | Use "spinner" or "spinner.{type}" to render a Spinner instead (Allowed types: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave, shimmer, caret, terminal, thinking).
                |
                */
                'unfiltered' => false,
                'recycle' => false,
                'select' => null,
                'indicator' => null,
            ],
        ],
        'signature' => Components\Signature\Component::class,
        'slide' => [
            Components\Slide\Component::class,
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
                |
                */
                'z-index' => 'z-50',
                'overflow' => false,
                'blur' => false,
                'persistent' => false,
                'size' => 'lg',
                'position' => 'right',
            ],
        ],
        'spinner' => [
            Components\Spinner\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Spinner Global Settings
                |----------------------------------------------------------------------
                |
                | type: controls the default variant (Allowed: ring, throbber, gradient,
                | ping, dots, pulse, typing, bars, wave, shimmer, caret, terminal, thinking).
                | size: controls the default size (Allowed: xs, sm, md, lg).
                |
                */
                'type' => 'ring',
                'size' => 'md',
            ],
        ],
        'stats' => [
            Components\Stats\Component::class,
            /*
            |----------------------------------------------------------------------
            | Stats Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
            ],
        ],
        'step' => [
            Components\Step\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Step Global Settings
            |----------------------------------------------------------------------
            |
            | helpers: controls the navigation buttons look (Allowed: default, minimal, compact, or a view path).
            |
            */
            [
                'helpers' => 'default',
            ],
        ],
        'step.items' => Components\Step\Items\Component::class,
        'swap' => [
            Components\Swap\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Swap Global Settings
                |----------------------------------------------------------------------
                |
                | preview: reveals slices of the previous and next options with a fade out effect.
                | vertical: swaps the options from top to bottom instead of sideways.
                | loop: allows navigating past the edges, cycling the options infinitely.
                | select: the default field mapping of the options (e.g., 'label:name|value:id').
                |
                */
                'preview' => false,
                'vertical' => false,
                'loop' => true,
                'select' => null,
            ],
        ],
        'tab' => [
            Components\Tab\Main\Component::class,
            /*
            |----------------------------------------------------------------------
            | Tab Global Settings
            |----------------------------------------------------------------------
            | shadowless: removes the shadow by default.
            | bordered: adds the border by default.
            |
            */
            [
                'shadowless' => false,
                'bordered' => false,
            ],
        ],
        'tag' => Components\Form\Tag\Component::class,
        'table' => [
            Components\Table\Component::class,
            /*
            |----------------------------------------------------------------------
            | Table Global Settings
            |----------------------------------------------------------------------
            |
            | paginator: controls the pagination look (Allowed: simple, minimal, compact, or a view path).
            | paginate: controls the rendering of the pagination.
            | simple-pagination: controls the reduction to previous and next only.
            | filter: controls the filter bar (true, or an array mapping the property names).
            | quantity: controls the options of the per-page select.
            | compact: tightens the padding of the cells, including the skeleton.
            | indicator: the default loading overlay. Null keeps the original icon.
            | Use "spinner" or "spinner.{type}" to render a Spinner instead (Allowed types: ring, throbber, gradient, ping, dots, pulse, typing, bars, wave, shimmer, caret, terminal, thinking).
            |
            */
            [
                'paginator' => 'simple',
                'paginate' => false,
                'simple-pagination' => false,
                'filter' => false,
                'quantity' => [10, 25, 50, 100],
                'compact' => false,
                'indicator' => null,
            ],
        ],
        'tab.items' => Components\Tab\Items\Component::class,
        'textarea' => Components\Form\Textarea\Component::class,
        'theme-switch' => Components\ThemeSwitch\Component::class,
        'time' => Components\Form\Time\Component::class,
        'timeline' => Components\Timeline\Main\Component::class,
        'timeline.items' => Components\Timeline\Items\Component::class,
        'toast' => [
            Components\Toast\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Toast Global Settings
                |----------------------------------------------------------------------
                |
                | z-index: controls the default z-index.
                | progress: enables the progress bar.
                | expandable: enables the expanded effect by default.
                | position: controls the default toast position (Allowed: top-right, top-left, top-center, bottom-right, bottom-left, bottom-center).
                | timeout: controls the default timeout in seconds.
                | stacked: piles the toasts on top of each other instead of listing them,
                | expanding the pile back into a list while the pointer is over it.
                | top-on-mobile: pins the toasts to the top of the screen below the md
                | breakpoint, whatever the position says. Off, they sit at the bottom.
                |
                */
                'z-index' => 'z-50',
                'progress' => true,
                'expandable' => false,
                'position' => 'top-right',
                'timeout' => 3,
                'stacked' => false,
                'top-on-mobile' => false,
            ],
        ],
        'toggle' => Components\Form\Toggle\Component::class,
        'tooltip' => [
            Components\Tooltip\Component::class,
            /*
            |----------------------------------------------------------------------
            | Tooltip Global Settings
            |----------------------------------------------------------------------
            |
            | delay: controls the pointer delay before opening (Allowed: slow, fast, faster, flash).
            | color: controls the balloon color (Allowed: any palette key, or black).
            | size: controls the balloon size (Allowed: sm, md, lg).
            | invert: controls the dark mode inversion of the default balloon.
            |
            */
            [
                'delay' => null,
                'color' => null,
                'size' => null,
                'invert' => false,
            ],
        ],
        'upload' => Components\Form\Upload\Component::class,
        'upload.async' => [
            Components\Form\Upload\Async\Component::class,
            [
                /*
                |----------------------------------------------------------------------
                | Async Upload Global Settings
                |----------------------------------------------------------------------
                |
                | chunk_size: controls the bytes sent on each chunk. (default 2 MB)
                | concurrency: controls how many chunks are uploaded in parallel, per component.
                | retries: controls the attempts per chunk on transient failures (5xx, 408, 429 and network).
                | retry_delay: controls the milliseconds between retries, with exponential backoff.
                | max_size: controls the maximum megabytes per file, on the client and on the handler (null = unlimited).
                | accept: controls the default mime/extension filter (null = any).
                | tmp_disk: controls the disk used to stage the chunks (must use the local driver).
                | tmp_directory: controls the directory, inside tmp_disk, used to stage the chunks.
                | disk: controls the destination disk of the finalized files (allowed: any driver).
                | keep: controls the seconds an unfinished upload is kept (discarded by tallstackui:async-upload:clear).
                |
                */
                'chunk_size' => 2 * 1024 * 1024,
                'concurrency' => 3,
                'retries' => 3,
                'retry_delay' => 1000,
                'max_size' => null,
                'accept' => null,
                'tmp_disk' => 'local',
                'tmp_directory' => 'async-uploads',
                'disk' => 'local',
                'keep' => 60 * 60 * 6,
            ],
        ],
        'reaction' => [
            Components\Reaction\Component::class,
            /*
            |----------------------------------------------------------------------
            | Reaction Global Settings
            |----------------------------------------------------------------------
            |
            | delay: controls the panel open/close animation (Allowed: slow, fast, faster, flash).
            | balloon: controls the panel color (Allowed: any palette key, or black).
            | hover: opens the panel when the pointer rests on the trigger.
            |
            */
            [
                'delay' => null,
                'balloon' => null,
                'hover' => false,
            ],
        ],
        'wrapper.input' => Components\Wrapper\Input\Component::class,
        'wrapper.radio' => Components\Wrapper\Radio\Component::class,
    ],
];
