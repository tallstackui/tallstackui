<?php

return [
    'environment' => [
        'environment' => 'Umgebung',
        'branch' => 'Branch',
    ],

    'errors' => [
        'title' => 'Es liegen :count Validierungsfehler vor:',
    ],

    'select' => [
        'default' => 'Auswählen',
        'search' => 'Suchen',
        'empty' => 'Keine Ergebnisse.',
        'selected' => ':count ausgewählt',
    ],

    'autocomplete' => [
        'default' => 'Tippen, um zu suchen...',
        'empty' => 'Keine Ergebnisse',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Bestätigen',
            'cancel' => 'Abbrechen',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Bestätigen',
            'cancel' => 'Abbrechen',
        ],
    ],

    'command-palette' => [
        'search' => 'Suchen...',
        'empty' => 'Keine Ergebnisse.',
        'navigate' => 'navigieren',
        'select' => 'auswählen',
        'close' => 'schließen',
    ],

    'table' => [
        'empty' => 'Keine Ergebnisse.',
        'quantity' => 'Menge',
        'search' => 'Suchen',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Kopieren',
            'copied' => 'Kopiert!',
        ],
    ],

    'password' => [
        'rules' => [
            'title' => 'Passwortanforderungen:',
            'formats' => [
                'min' => 'Mindestens :min Zeichen',
                'numbers' => 'Mindestens eine Nummer',
                'symbols' => 'Mindestens ein Symbol (:symbols)',
                'mixed' => 'Groß- und Kleinbuchstaben',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Datei auswählen',
        'size' => 'Größe',
        'upload' => 'Hochladen',
        'uploaded' => [
            'single' => ':count Datei hochgeladen.',
            'multiple' => ':count Dateien hochgeladen.',
        ],
        'error' => 'Ein Fehler ist aufgetreten. Bitte versuche es erneut.',
        'static' => [
            'empty' => [
                'title' => 'Keine Bilder',
                'description' => 'Sie haben noch keine Bilder.',
            ],
        ],
        'invalid' => 'Es gab einen Validierungsfehler.',
    ],

    'upload_async' => [
        'title' => 'Dateien hier ablegen',
        'description' => 'oder klicken zum Auswählen',
        'send' => 'Senden',
        'clear' => 'Leeren',
        'ready' => [
            'single' => ':count Datei bereit · :size',
            'multiple' => ':count Dateien bereit · :size',
        ],
        'errors' => [
            'mime' => 'Dateityp nicht erlaubt.',
            'size' => 'Die Datei überschreitet das Limit von :max MB.',
            'limit' => 'Du kannst höchstens :max Dateien hochladen.',
            'network' => 'Netzwerkfehler. Bitte versuche es erneut.',
            'server' => 'Hochladen fehlgeschlagen. Bitte versuche es erneut.',
            'integrity' => 'Der Upload ist unvollständig angekommen. Bitte versuche es erneut.',
            'unauthorized' => 'Du darfst diese Datei nicht hochladen.',
            'generic' => 'Ein Fehler ist aufgetreten.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Januar',
                'february' => 'Februar',
                'march' => 'März',
                'april' => 'April',
                'may' => 'Mai',
                'june' => 'Juni',
                'july' => 'Juli',
                'august' => 'August',
                'september' => 'September',
                'october' => 'Oktober',
                'november' => 'November',
                'december' => 'Dezember',
            ],
            'week' => [
                'sunday' => 'Sonntag',
                'monday' => 'Montag',
                'tuesday' => 'Dienstag',
                'wednesday' => 'Mittwoch',
                'thursday' => 'Donnerstag',
                'friday' => 'Freitag',
                'saturday' => 'Samstag',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Gestern',
            'today' => 'Heute',
            'tomorrow' => 'Morgen',
        ],
    ],

    'time' => [
        'helper' => 'Aktuelle Uhrzeit',
    ],

    'step' => [
        'next' => 'Weiter',
        'previous' => 'Zurück',
        'finish' => 'Beenden',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'SCHLÜSSEL',
            'value' => 'WERT',
        ],
        'placeholders' => [
            'key' => 'Schlüssel eingeben',
            'value' => 'Wert eingeben',
        ],
        'add-row' => 'ZEILE HINZUFÜGEN',
        'empty' => 'Keine Zeilen hinzugefügt.',
    ],

    'currency' => [
        'symbol' => '€',
        'currency' => 'EUR',
    ],

    'list' => [
        'search' => 'Suchen',
        'empty' => 'Keine Einträge.',
    ],
];
