<?php

return [
    'environment' => [
        'environment' => 'Omgeving',
        'branch' => 'Branch',
    ],

    'errors' => [
        'title' => 'Er zijn :count validatie fouten:',
    ],

    'select' => [
        'default' => 'Kies een optie',
        'search' => 'Zoek hier',
        'empty' => 'Geen resultaten gevonden',
        'selected' => ':count geselecteerd',
    ],

    'tag' => [
        'empty' => 'Geen resultaten gevonden',
    ],

    'autocomplete' => [
        'default' => 'Typ om te zoeken...',
        'empty' => 'Geen resultaten gevonden',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Bevestig',
            'cancel' => 'Annuleer',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Bevestig',
            'cancel' => 'Annuleer',
        ],
    ],

    'command-palette' => [
        'search' => 'Zoeken...',
        'empty' => 'Geen resultaten gevonden.',
        'navigate' => 'navigeren',
        'select' => 'selecteren',
        'close' => 'sluiten',
    ],

    'table' => [
        'empty' => 'Geen resultaten gevonden.',
        'quantity' => 'Aantal',
        'search' => 'Zoek hier',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Kopieer',
            'copied' => 'Gekopieerd!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Kopiëren',
        'download' => 'Downloaden',
    ],

    'password' => [
        'rules' => [
            'title' => 'Verwacht Wachtwoord format:',
            'formats' => [
                'min' => 'Tenminste :min karakters',
                'numbers' => 'Tenminste 1 getal',
                'symbols' => 'Tenminste 1 symbool (:symbols)',
                'mixed' => 'Hoofd- en kleine letters',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Kies een bestand',
        'size' => 'Grootte',
        'upload' => 'Klik hier om op te laden',
        'uploaded' => [
            'single' => ':count bestand verzonden',
            'multiple' => ':count bestanden verzonden',
        ],
        'error' => 'Er ging iets mis. Probeer opnieuw.',
        'static' => [
            'empty' => [
                'title' => 'Geen beelden.',
                'description' => 'Je hebt nog geen enkel beeld.',
            ],
        ],
        'invalid' => 'Er trad een validatiefout op.',
    ],

    'upload_async' => [
        'title' => 'Sleep bestanden hierheen',
        'description' => 'of klik om te selecteren',
        'send' => 'Versturen',
        'clear' => 'Wissen',
        'ready' => [
            'single' => ':count bestand gereed · :size',
            'multiple' => ':count bestanden gereed · :size',
        ],
        'errors' => [
            'mime' => 'Bestandstype niet toegestaan.',
            'size' => 'Het bestand overschrijdt de limiet van :max MB.',
            'limit' => 'Je kunt maximaal :max bestanden opladen.',
            'network' => 'Netwerkfout. Probeer opnieuw.',
            'server' => 'Opladen mislukt. Probeer opnieuw.',
            'integrity' => 'De upload is onvolledig aangekomen. Probeer opnieuw.',
            'unauthorized' => 'Je mag dit bestand niet opladen.',
            'generic' => 'Er ging iets mis.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Januari',
                'february' => 'Februari',
                'march' => 'Maart',
                'april' => 'April',
                'may' => 'Mei',
                'june' => 'Juni',
                'july' => 'Juli',
                'august' => 'Augustus',
                'september' => 'September',
                'october' => 'Oktober',
                'november' => 'November',
                'december' => 'December',
            ],
            'week' => [
                'sunday' => 'Zondag',
                'monday' => 'Maandag',
                'tuesday' => 'Dinsdag',
                'wednesday' => 'Woensdag',
                'thursday' => 'Donderdag',
                'friday' => 'Vrijdag',
                'saturday' => 'Zaterdag',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Gisteren',
            'today' => 'Vandaag',
            'tomorrow' => 'Morgen',
        ],
    ],

    'time' => [
        'helper' => 'Huidige Tijd',
    ],

    'step' => [
        'next' => 'Volgende',
        'previous' => 'Vorige',
        'finish' => 'Voltooien',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'SLEUTEL',
            'value' => 'WAARDE',
        ],
        'placeholders' => [
            'key' => 'Voer een sleutel in',
            'value' => 'Voer een waarde in',
        ],
        'add-row' => 'RIJ TOEVOEGEN',
        'empty' => 'Geen rijen toegevoegd.',
    ],

    'currency' => [
        'symbol' => '€',
        'currency' => 'EUR',
    ],

    'list' => [
        'search' => 'Zoeken',
        'empty' => 'Geen items.',
    ],

    'editor' => [
        'placeholder' => 'Begin met schrijven...',
        'tooltip' => [
            'style' => 'Alineastijl',
            'blockquote' => 'Citaat',
            'bold' => 'Vet',
            'italic' => 'Cursief',
            'underline' => 'Onderstrepen',
            'strikethrough' => 'Doorhalen',
            'ordered_list' => 'Genummerde lijst',
            'unordered_list' => 'Opsommingslijst',
            'indent' => 'Inspringing vergroten',
            'outdent' => 'Inspringing verkleinen',
            'align' => 'Uitlijning',
            'code' => 'Code',
            'code_block' => 'Codeblok',
            'clear_format' => 'Opmaak wissen',
            'link' => 'Link invoegen',
            'image' => 'Afbeelding invoegen',
            'hr' => 'Horizontale lijn',
            'undo' => 'Ongedaan maken',
            'redo' => 'Opnieuw',
            'fullscreen' => 'Volledig scherm',
        ],
        'style' => [
            'paragraph' => 'Alinea',
            'h1' => 'Kop 1',
            'h2' => 'Kop 2',
            'h3' => 'Kop 3',
        ],
        'align' => [
            'left' => 'Links',
            'center' => 'Gecentreerd',
            'right' => 'Rechts',
            'justify' => 'Uitgevuld',
        ],
        'image' => [
            'title' => 'Afbeelding invoegen',
            'upload' => 'Bestand kiezen',
            'upload_hint' => 'PNG, JPG, GIF of WebP tot :size',
            'or_url' => 'of plak een URL',
            'url' => 'URL',
            'alt' => 'Alternatieve tekst',
            'alt_hint' => 'Beschrijf de afbeelding voor schermlezers',
            'cancel' => 'Annuleren',
            'insert' => 'Invoegen',
            'errors' => [
                'url' => 'Ongeldige of onbereikbare URL.',
                'mime' => 'Bestandstype niet toegestaan.',
                'size' => 'Het bestand overschrijdt de limiet van :max KB.',
                'failed' => 'Uploaden mislukt. Probeer het opnieuw.',
            ],
        ],
        'link' => [
            'title' => 'Link invoegen',
            'text' => 'Tekst',
            'url' => 'URL',
            'cancel' => 'Annuleren',
            'insert' => 'Invoegen',
        ],
        'counters' => [
            'words' => ':count woord|:count woorden',
            'lines' => ':count regel|:count regels',
        ],
    ],

    'spinner' => [
        'thinking' => 'Aan het denken...',
        'loading' => 'Laden...',
    ],
];
