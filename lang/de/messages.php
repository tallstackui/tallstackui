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

    'tag' => [
        'empty' => 'Keine Ergebnisse',
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

    'qr-code' => [
        'copy' => 'Kopieren',
        'download' => 'Herunterladen',
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

    'editor' => [
        'placeholder' => 'Schreiben Sie los...',
        'tooltip' => [
            'style' => 'Absatzformat',
            'blockquote' => 'Zitat',
            'bold' => 'Fett',
            'italic' => 'Kursiv',
            'underline' => 'Unterstrichen',
            'strikethrough' => 'Durchgestrichen',
            'ordered_list' => 'Nummerierte Liste',
            'unordered_list' => 'Aufzählung',
            'indent' => 'Einzug vergrößern',
            'outdent' => 'Einzug verkleinern',
            'align' => 'Ausrichtung',
            'code' => 'Code',
            'code_block' => 'Codeblock',
            'clear_format' => 'Formatierung entfernen',
            'link' => 'Link einfügen',
            'image' => 'Bild einfügen',
            'hr' => 'Trennlinie',
            'undo' => 'Rückgängig',
            'redo' => 'Wiederholen',
            'fullscreen' => 'Vollbild',
        ],
        'style' => [
            'paragraph' => 'Absatz',
            'h1' => 'Überschrift 1',
            'h2' => 'Überschrift 2',
            'h3' => 'Überschrift 3',
        ],
        'align' => [
            'left' => 'Links',
            'center' => 'Zentriert',
            'right' => 'Rechts',
            'justify' => 'Blocksatz',
        ],
        'image' => [
            'title' => 'Bild einfügen',
            'upload' => 'Datei auswählen',
            'upload_hint' => 'PNG, JPG, GIF oder WebP bis :size',
            'or_url' => 'oder eine URL einfügen',
            'url' => 'URL',
            'alt' => 'Alternativtext',
            'alt_hint' => 'Beschreiben Sie das Bild für Screenreader',
            'cancel' => 'Abbrechen',
            'insert' => 'Einfügen',
            'errors' => [
                'url' => 'Ungültige oder nicht erreichbare URL.',
                'mime' => 'Dateityp nicht erlaubt.',
                'size' => 'Die Datei überschreitet das Limit von :max KB.',
                'failed' => 'Upload fehlgeschlagen. Bitte erneut versuchen.',
            ],
        ],
        'link' => [
            'title' => 'Link einfügen',
            'text' => 'Text',
            'url' => 'URL',
            'cancel' => 'Abbrechen',
            'insert' => 'Einfügen',
        ],
        'counters' => [
            'words' => ':count Wort|:count Wörter',
            'lines' => ':count Zeile|:count Zeilen',
        ],
    ],

    'spinner' => [
        'thinking' => 'Denkt nach...',
        'loading' => 'Wird geladen...',
    ],
];
