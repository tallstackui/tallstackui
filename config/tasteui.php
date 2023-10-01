<?php

use TasteUi\View\Components;

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
    | Components
    |--------------------------------------------------------------------------
    |
    | List of all TasteUi components.
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
        //        'wrapper.radio' => Components\Wrapper\Radio::class,
        //        'wrapper.toggle' => Components\Wrapper\Toggle::class,
        //        'wrapper.select' => Components\Wrapper\Select::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Wrappers
    |--------------------------------------------------------------------------
    |
    | Configuration of wrapper components. Wrappers are TasteUi components that do
    | not have anonymous PHP - Blade component classes, and only serve to store the
    | base HTML of some components, such as: form input, radio, toggle and styled selects.
    */
    'wrappers' => [
        /* Form Wrappers */
        'form' => [
            /* Input Wrapper */
            'input' => 'relative mt-2 rounded-md shadow-sm',
            /* Radio & Toggle Wrapper */
            'radio-toggle' => [
                'wrapper' => 'flex items-center',
                'label' => [
                    'span' => 'text-sm',
                    'p' => 'font-medium text-gray-700',
                    'error' => 'text-red-600',
                ],
                'slot' => 'relative inline-flex cursor-pointer items-center',
            ],
        ],
        /* Select Wrapper */
        'select' => [
            'wrapper' => 'relative mt-2',
            'div' => [
                'input' => 'flex w-full cursor-pointer items-center gap-x-2 rounded-md border-0 bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6',
                'error' => 'text-red-600 ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500',
            ],
            'header' => 'relative inset-y-0 left-0 flex w-full items-center overflow-hidden rounded-lg pl-2 transition space-x-2',
            'buttons' => [
                'wrapper' => 'mr-1 flex items-center',
                'x-mark' => [
                    'base' => 'h-5 w-5 transition hover:text-red-500',
                    'normal' => 'text-secondary-500',
                    'error' => 'text-red-500',
                ],
                'up-down' => [
                    'base' => 'h-5 w-5 transition',
                    'normal' => 'text-secondary-500',
                    'error' => 'text-red-500',
                ],
            ],
            'box' => [
                'wrapper' => 'absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5',
                'button' => [
                    'base' => 'absolute inset-y-0 right-2 flex cursor-pointer items-center px-2',
                    'icon' => 'h-5 w-5 transition text-secondary-500 hover:text-red-500',
                ],
                'list' => [
                    'wrapper' => 'z-50 mt-1 max-h-60 w-full overflow-auto rounded-b-lg bg-white text-base soft-scrollbar focus:outline-none sm:text-sm',
                    'loading' => [
                        'wrapper' => 'flex items-center justify-center p-4 space-x-4',
                        'base' => 'h-12 w-12 animate-spin text-primary-600',
                    ],
                    'item' => [
                        'wrapper' => 'relative cursor-pointer select-none px-2 py-2 text-gray-700 transition hover:bg-gray-100',
                        'base' => 'flex items-center justify-between',
                    ],
                ],
            ],
            'message' => 'block w-full pr-2 text-gray-700',
        ],
    ],
];
