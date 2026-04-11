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

    'comments' => [
        'title' => 'Comments',
        'counter' => '{0} No comments yet|{1} :count comment|[2,*] :count comments',
        'hint' => 'Write something useful and respectful.',
        'policy' => 'Pending comments stay visible to their author when moderation is enabled.',
        'empty' => 'No comments yet. Start the conversation.',
        'pending' => 'Pending review',
        'edited' => 'Edited',
        'login_required' => 'You need to be authenticated to comment here.',
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
            'website' => 'Website',
            'comment' => 'Comment',
        ],
        'sort' => [
            'label' => 'Sort',
            'latest' => 'Latest first',
            'oldest' => 'Oldest first',
            'popular' => 'Most replied',
        ],
        'actions' => [
            'comment' => 'Publish comment',
            'reply' => 'Reply',
            'send_reply' => 'Publish reply',
            'edit' => 'Edit',
            'save' => 'Save changes',
            'delete' => 'Delete',
            'cancel' => 'Cancel',
            'approve' => 'Approve',
        ],
        'errors' => [
            'comment' => 'You are not allowed to publish comments right now.',
            'reply' => 'You are not allowed to reply to this comment.',
            'edit' => 'You are not allowed to edit this comment.',
            'delete' => 'You are not allowed to delete this comment.',
        ],
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copy',
            'copied' => 'Copied!',
        ],
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
];
