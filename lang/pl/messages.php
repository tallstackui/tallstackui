<?php

return [
    'environment' => [
        'environment' => 'Środowisko',
        'branch' => 'Gałąź',
    ],

    'errors' => [
        'title' => 'Wystąpiły :count błędy walidacji:',
    ],

    'select' => [
        'default' => 'Wybierz opcję',
        'search' => 'Szukaj tutaj',
        'empty' => 'Nie znaleziono rezultatów',
        'selected' => ':count wybranych',
    ],

    'tag' => [
        'empty' => 'Nie znaleziono rezultatów',
    ],

    'autocomplete' => [
        'default' => 'Wpisz, aby wyszukać...',
        'empty' => 'Nie znaleziono rezultatów',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Potwierdzam',
            'cancel' => 'Anuluj',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Potwierdzam',
            'cancel' => 'Anuluj',
        ],
    ],

    'command-palette' => [
        'search' => 'Szukaj...',
        'empty' => 'Nie znaleziono rezultatów.',
        'navigate' => 'nawiguj',
        'select' => 'wybierz',
        'close' => 'zamknij',
    ],

    'table' => [
        'empty' => 'Nie znaleziono rezultatów',
        'quantity' => 'Ilość',
        'search' => 'Szukaj tutaj',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Kopiuj',
            'copied' => 'Skopiowane!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Kopiuj',
        'download' => 'Pobierz',
    ],

    'password' => [
        'rules' => [
            'title' => 'Oczekiwany format hasła:',
            'formats' => [
                'min' => 'Co najmniej :min znaków',
                'numbers' => 'Co najmniej jedna liczba',
                'symbols' => 'Co najmniej jeden symbol (:symbols)',
                'mixed' => 'Wielkie i małe litery',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Wybierz plik',
        'size' => 'Rozmiar',
        'upload' => 'Kliknij tutaj, aby przesłać plik',
        'uploaded' => [
            'single' => ':count wysłany plik',
            'multiple' => ':count wysyłanie pliki',
        ],
        'error' => 'Coś poszło nie tak. Spróbuj ponownie.',
        'static' => [
            'empty' => [
                'title' => 'Brak obrazów.',
                'description' => 'Nie posiadasz jeszcze żadnego obrazka.',
            ],
        ],
        'invalid' => 'Wystąpił błąd walidacji.',
    ],

    'upload_async' => [
        'title' => 'Upuść pliki tutaj',
        'description' => 'lub kliknij, aby wybrać',
        'send' => 'Wyślij',
        'clear' => 'Wyczyść',
        'ready' => [
            'single' => ':count plik gotowy · :size',
            'multiple' => ':count pliki gotowe · :size',
        ],
        'errors' => [
            'mime' => 'Niedozwolony typ pliku.',
            'size' => 'Plik przekracza limit :max MB.',
            'limit' => 'Możesz przesłać maksymalnie :max plików.',
            'network' => 'Błąd sieci. Spróbuj ponownie.',
            'server' => 'Przesyłanie nie powiodło się. Spróbuj ponownie.',
            'integrity' => 'Przesłany plik dotarł niekompletny. Spróbuj ponownie.',
            'unauthorized' => 'Nie masz uprawnień do przesłania tego pliku.',
            'generic' => 'Coś poszło nie tak.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Styczeń',
                'february' => 'Luty',
                'march' => 'Marzec',
                'april' => 'Kwiecień',
                'may' => 'Maj',
                'june' => 'Czerwiec',
                'july' => 'Lipiec',
                'august' => 'Sierpień',
                'september' => 'Wrzesień',
                'october' => 'Październik',
                'november' => 'Listopad',
                'december' => 'Grudzień',
            ],
            'week' => [
                'sunday' => 'Niedziela',
                'monday' => 'Poniedziałek',
                'tuesday' => 'Wtorek',
                'wednesday' => 'Środa',
                'thursday' => 'Czwartek',
                'friday' => 'Piątek',
                'saturday' => 'Sobota',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Wczoraj',
            'today' => 'Dzisiaj',
            'tomorrow' => 'Jutro',
        ],
    ],

    'time' => [
        'helper' => 'Aktualny czas',
    ],

    'step' => [
        'next' => 'Następny',
        'previous' => 'Poprzedni',
        'finish' => 'Zakończ',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'KLUCZ',
            'value' => 'WARTOŚĆ',
        ],
        'placeholders' => [
            'key' => 'Wprowadź klucz',
            'value' => 'Wprowadź wartość',
        ],
        'add-row' => 'DODAJ WIERSZ',
        'empty' => 'Nie dodano żadnych wierszy.',
    ],

    'currency' => [
        'symbol' => 'zł',
        'currency' => 'PLN',
    ],

    'list' => [
        'search' => 'Szukaj',
        'empty' => 'Brak elementów.',
    ],

    'editor' => [
        'placeholder' => 'Zacznij pisać...',
        'tooltip' => [
            'style' => 'Styl akapitu',
            'blockquote' => 'Cytat',
            'bold' => 'Pogrubienie',
            'italic' => 'Kursywa',
            'underline' => 'Podkreślenie',
            'strikethrough' => 'Przekreślenie',
            'ordered_list' => 'Lista numerowana',
            'unordered_list' => 'Lista punktowana',
            'indent' => 'Zwiększ wcięcie',
            'outdent' => 'Zmniejsz wcięcie',
            'align' => 'Wyrównanie',
            'code' => 'Kod',
            'code_block' => 'Blok kodu',
            'clear_format' => 'Wyczyść formatowanie',
            'link' => 'Wstaw link',
            'image' => 'Wstaw obraz',
            'hr' => 'Linia pozioma',
            'undo' => 'Cofnij',
            'redo' => 'Ponów',
            'fullscreen' => 'Pełny ekran',
        ],
        'style' => [
            'paragraph' => 'Akapit',
            'h1' => 'Nagłówek 1',
            'h2' => 'Nagłówek 2',
            'h3' => 'Nagłówek 3',
        ],
        'align' => [
            'left' => 'Do lewej',
            'center' => 'Do środka',
            'right' => 'Do prawej',
            'justify' => 'Wyjustowany',
        ],
        'image' => [
            'title' => 'Wstaw obraz',
            'upload' => 'Wybierz plik',
            'upload_hint' => 'PNG, JPG, GIF lub WebP do :size',
            'or_url' => 'albo wklej adres URL',
            'url' => 'URL',
            'alt' => 'Tekst alternatywny',
            'alt_hint' => 'Opisz obraz dla czytników ekranu',
            'cancel' => 'Anuluj',
            'insert' => 'Wstaw',
            'errors' => [
                'url' => 'Nieprawidłowy lub niedostępny adres URL.',
                'mime' => 'Niedozwolony typ pliku.',
                'size' => 'Plik przekracza limit :max KB.',
                'failed' => 'Przesyłanie nie powiodło się. Spróbuj ponownie.',
            ],
        ],
        'link' => [
            'title' => 'Wstaw link',
            'text' => 'Tekst',
            'url' => 'URL',
            'cancel' => 'Anuluj',
            'insert' => 'Wstaw',
        ],
        'counters' => [
            'words' => ':count słowo|:count słowa',
            'lines' => ':count wiersz|:count wiersze',
        ],
    ],

    'spinner' => [
        'thinking' => 'Myślę...',
        'loading' => 'Ładowanie...',
    ],
];
