<?php

return [
    'environment' => [
        'environment' => 'Ambiente',
        'branch' => 'Ramo',
    ],

    'errors' => [
        'title' => 'Sono presenti :count errori di convalida:',
    ],

    'select' => [
        'default' => "Seleziona un'opzione",
        'search' => 'Cerca qualcosa qui',
        'empty' => 'Nessun risultato trovato',
        'selected' => ':count selezionati',
    ],

    'autocomplete' => [
        'default' => 'Digita per cercare...',
        'empty' => 'Nessun risultato trovato',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confermare',
            'cancel' => 'Annulla',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confermare',
            'cancel' => 'Annulla',
        ],
    ],

    'command-palette' => [
        'search' => 'Cerca...',
        'empty' => 'Nessun risultato trovato.',
        'navigate' => 'naviga',
        'select' => 'seleziona',
        'close' => 'chiudi',
    ],

    'table' => [
        'empty' => 'Nessun risultato trovato.',
        'quantity' => 'Quantità',
        'search' => 'Cerca qualcosa qui',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copia',
            'copied' => 'Copiato!',
        ],
    ],

    'password' => [
        'rules' => [
            'title' => 'Formato password previsto:',
            'formats' => [
                'min' => 'Almeno :min caratteri',
                'numbers' => 'Almeno un numero',
                'symbols' => 'Almeno un simbolo (:symbols)',
                'mixed' => 'Lettere maiuscole e minuscole',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Scegli un file',
        'size' => 'Dimensione',
        'upload' => 'Clicca qui per inviare',
        'uploaded' => [
            'single' => ':count file inviato',
            'multiple' => ':count file inviati',
        ],
        'error' => 'Qualcosa è andato storto. Per favore riprova.',
        'static' => [
            'empty' => [
                'title' => 'Nessuna immagine.',
                'description' => 'Non hai ancora nessuna immagine.',
            ],
        ],
        'invalid' => 'Si è verificato un errore di convalida.',
    ],

    'upload_async' => [
        'title' => 'Trascina i file qui',
        'description' => 'oppure clicca per selezionare',
        'send' => 'Invia',
        'clear' => 'Svuota',
        'ready' => [
            'single' => ':count file pronto · :size',
            'multiple' => ':count file pronti · :size',
        ],
        'errors' => [
            'mime' => 'Tipo di file non consentito.',
            'size' => 'Il file supera il limite di :max MB.',
            'limit' => 'Puoi inviare al massimo :max file.',
            'network' => 'Errore di rete. Per favore riprova.',
            'server' => 'L\'invio non è riuscito. Per favore riprova.',
            'integrity' => 'L\'invio è arrivato incompleto. Per favore riprova.',
            'unauthorized' => 'Non sei autorizzato a inviare questo file.',
            'generic' => 'Qualcosa è andato storto.',
        ],
        'remove' => 'Rimuovi file',
        'preview' => [
            'close' => 'Chiudi anteprima',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Gennaio',
                'february' => 'Febbraio',
                'march' => 'Marzo',
                'april' => 'Aprile',
                'may' => 'Maggio',
                'june' => 'Giugno',
                'july' => 'Luglio',
                'august' => 'Agosto',
                'september' => 'Settembre',
                'october' => 'Ottobre',
                'november' => 'Novembre',
                'december' => 'Dicembre',
            ],
            'week' => [
                'sunday' => 'Domenica',
                'monday' => 'Lunedì',
                'tuesday' => 'Martedì',
                'wednesday' => 'Mercoledì',
                'thursday' => 'Giovedì',
                'friday' => 'Venerdì',
                'saturday' => 'Sabato',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Ieri',
            'today' => 'Oggi',
            'tomorrow' => 'Domani',
        ],
    ],

    'time' => [
        'helper' => 'Ora Attuale',
    ],

    'step' => [
        'next' => 'Avanti',
        'previous' => 'Precedente',
        'finish' => 'Fine',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'CHIAVE',
            'value' => 'VALORE',
        ],
        'placeholders' => [
            'key' => 'Inserisci una chiave',
            'value' => 'Inserisci un valore',
        ],
        'add-row' => 'AGGIUNGI RIGA',
        'empty' => 'Nessuna riga aggiunta.',
    ],

    'currency' => [
        'symbol' => '€',
        'currency' => 'EUR',
    ],

    'list' => [
        'search' => 'Cerca',
        'empty' => 'Nessun elemento.',
    ],
];
