<?php

return [
    'environment' => [
        'environment' => 'Environment',
        'branch' => 'Branch',
    ],

    'errors' => [
        'title' => 'There are :count validation errors:',
    ],

    'select' => [
        'default' => 'Select an option',
        'search' => 'Search something here',
        'empty' => 'No results found',
        'selected' => ':count selected',
    ],

    'tag' => [
        'empty' => 'No results found',
    ],

    'autocomplete' => [
        'default' => 'Type to search...',
        'empty' => 'No results found',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirm',
            'cancel' => 'Cancel',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirm',
            'cancel' => 'Cancel',
        ],
    ],

    'command-palette' => [
        'search' => 'Search...',
        'empty' => 'No results found.',
        'navigate' => 'navigate',
        'select' => 'select',
        'close' => 'close',
    ],

    'table' => [
        'empty' => 'No results found.',
        'quantity' => 'Quantity',
        'search' => 'Search something here',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copy',
            'copied' => 'Copied!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Copy',
        'download' => 'Download',
    ],

    'password' => [
        'rules' => [
            'title' => 'Expected Password Format:',
            'formats' => [
                'min' => 'At least :min characters',
                'numbers' => 'At least one number',
                'symbols' => 'At least one symbol (:symbols)',
                'mixed' => 'Uppercase and lowercase letters',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Choose a file',
        'size' => 'Size',
        'upload' => 'Click here to upload',
        'uploaded' => [
            'single' => ':count file sent',
            'multiple' => ':count files sent',
        ],
        'error' => 'Something went wrong. Please, try again.',
        'static' => [
            'empty' => [
                'title' => 'No images.',
                'description' => 'You don\'t have any image yet.',
            ],
        ],
        'invalid' => 'There was some validation error.',
    ],

    'upload_async' => [
        'title' => 'Drop files here',
        'description' => 'or click to select',
        'send' => 'Send',
        'clear' => 'Clear',
        'ready' => [
            'single' => ':count file ready · :size',
            'multiple' => ':count files ready · :size',
        ],
        'errors' => [
            'mime' => 'File type not allowed.',
            'size' => 'File exceeds the :max MB limit.',
            'limit' => 'You can upload at most :max files.',
            'network' => 'Network error. Please try again.',
            'server' => 'Upload failed. Please try again.',
            'integrity' => 'The upload arrived incomplete. Please try again.',
            'unauthorized' => 'You are not allowed to upload this file.',
            'generic' => 'Something went wrong.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'January',
                'february' => 'February',
                'march' => 'March',
                'april' => 'April',
                'may' => 'May',
                'june' => 'June',
                'july' => 'July',
                'august' => 'August',
                'september' => 'September',
                'october' => 'October',
                'november' => 'November',
                'december' => 'December',
            ],
            'week' => [
                'sunday' => 'Sunday',
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Yesterday',
            'today' => 'Today',
            'tomorrow' => 'Tomorrow',
        ],
    ],

    'time' => [
        'helper' => 'Current Time',
    ],

    'step' => [
        'next' => 'Next',
        'previous' => 'Previous',
        'finish' => 'Finish',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'KEY',
            'value' => 'VALUE',
        ],
        'placeholders' => [
            'key' => 'Enter a key',
            'value' => 'Enter a value',
        ],
        'add-row' => 'ADD ROW',
        'empty' => 'No rows added.',
    ],

    'currency' => [
        'symbol' => '$',
        'currency' => 'USD',
    ],

    'list' => [
        'search' => 'Search',
        'empty' => 'No items.',
    ],

    'editor' => [
        'placeholder' => 'Start writing...',
        'tooltip' => [
            'style' => 'Paragraph style',
            'blockquote' => 'Quote',
            'bold' => 'Bold',
            'italic' => 'Italic',
            'underline' => 'Underline',
            'strikethrough' => 'Strikethrough',
            'ordered_list' => 'Numbered list',
            'unordered_list' => 'Bulleted list',
            'indent' => 'Increase indent',
            'outdent' => 'Decrease indent',
            'align' => 'Alignment',
            'code' => 'Code',
            'code_block' => 'Code block',
            'clear_format' => 'Clear formatting',
            'link' => 'Insert link',
            'image' => 'Insert image',
            'hr' => 'Horizontal rule',
            'undo' => 'Undo',
            'redo' => 'Redo',
            'fullscreen' => 'Fullscreen',
        ],
        'style' => [
            'paragraph' => 'Paragraph',
            'h1' => 'Heading 1',
            'h2' => 'Heading 2',
            'h3' => 'Heading 3',
        ],
        'align' => [
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
            'justify' => 'Justify',
        ],
        'image' => [
            'title' => 'Insert image',
            'upload' => 'Choose file',
            'upload_hint' => 'PNG, JPG, GIF or WebP up to :size',
            'or_url' => 'or paste a URL',
            'url' => 'URL',
            'alt' => 'Alternative text',
            'alt_hint' => 'Describe the image for screen readers',
            'cancel' => 'Cancel',
            'insert' => 'Insert',
            'errors' => [
                'url' => 'Invalid or unreachable URL.',
                'mime' => 'File type not allowed.',
                'size' => 'File exceeds the :max KB limit.',
                'failed' => 'Upload failed. Please try again.',
            ],
        ],
        'link' => [
            'title' => 'Insert link',
            'text' => 'Text',
            'url' => 'URL',
            'cancel' => 'Cancel',
            'insert' => 'Insert',
        ],
        'counters' => [
            'words' => ':count word|:count words',
            'lines' => ':count line|:count lines',
        ],
    ],

    'spinner' => [
        'thinking' => 'Thinking...',
        'loading' => 'Loading...',
    ],
];
