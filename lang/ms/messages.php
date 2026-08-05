<?php

return [
    'environment' => [
        'environment' => 'Persekitaran',
        'branch' => 'Cawangan',
    ],

    'errors' => [
        'title' => 'Terdapat :count ralat pengesahan:',
    ],

    'select' => [
        'default' => 'Pilihan',
        'search' => 'Carian',
        'empty' => 'Tiada data dijumpai',
        'selected' => ':count dipilih',
    ],

    'tag' => [
        'empty' => 'Tiada data dijumpai',
    ],

    'autocomplete' => [
        'default' => 'Taip untuk mencari...',
        'empty' => 'Tiada data dijumpai',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Pasti',
            'cancel' => 'Batal',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Pasti',
            'cancel' => 'Batal',
        ],
    ],

    'command-palette' => [
        'search' => 'Carian...',
        'empty' => 'Tiada data dijumpai.',
        'navigate' => 'navigasi',
        'select' => 'pilih',
        'close' => 'tutup',
    ],

    'table' => [
        'empty' => 'Tiada data dijumpai',
        'quantity' => 'Kuantiti',
        'search' => 'Carian',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Salin',
            'copied' => 'Disalin!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Salin',
        'download' => 'Muat turun',
    ],

    'password' => [
        'rules' => [
            'title' => 'Format Kata Laluan:',
            'formats' => [
                'min' => 'Mestilah mempunyai sekurang-kurangnya :min karakter',
                'numbers' => 'Mestilah mempunyai sekurang-kurangnya satu nombor',
                'symbols' => 'Mestilah mempunyai sekurang-kurangnya satu simbol (:symbols)',
                'mixed' => 'Huruf besar dan huruf kecil',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Pilih fail',
        'size' => 'Saiz',
        'upload' => 'Klik untuk muat naik',
        'uploaded' => [
            'single' => ':count fail dihantar',
            'multiple' => ':count fail dihantar',
        ],
        'error' => 'Terdapat ralat. Sila cuba lagi.',
        'static' => [
            'empty' => [
                'title' => 'Tiada gambar.',
                'description' => 'Anda tidak mempunyai gambar.',
            ],
        ],
        'invalid' => 'Terdapat ralat pengesahan.',
    ],

    'upload_async' => [
        'title' => 'Lepaskan fail di sini',
        'description' => 'atau klik untuk memilih',
        'send' => 'Hantar',
        'clear' => 'Kosongkan',
        'ready' => [
            'single' => ':count fail sedia · :size',
            'multiple' => ':count fail sedia · :size',
        ],
        'errors' => [
            'mime' => 'Jenis fail tidak dibenarkan.',
            'size' => 'Fail melebihi had :max MB.',
            'limit' => 'Anda hanya boleh memuat naik :max fail sahaja.',
            'network' => 'Ralat rangkaian. Sila cuba lagi.',
            'server' => 'Muat naik gagal. Sila cuba lagi.',
            'integrity' => 'Muat naik diterima tidak lengkap. Sila cuba lagi.',
            'unauthorized' => 'Anda tidak dibenarkan memuat naik fail ini.',
            'generic' => 'Terdapat ralat.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Januari',
                'february' => 'Februari',
                'march' => 'Mac',
                'april' => 'April',
                'may' => 'Mei',
                'june' => 'Jun',
                'july' => 'Julai',
                'august' => 'Ogos',
                'september' => 'September',
                'october' => 'Oktober',
                'november' => 'November',
                'december' => 'Disember',
            ],
            'week' => [
                'sunday' => 'Ahad',
                'monday' => 'Isnin',
                'tuesday' => 'Selasa',
                'wednesday' => 'Rabu',
                'thursday' => 'Khamis',
                'friday' => 'Jumaat',
                'saturday' => 'Sabtu',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Semalam',
            'today' => 'Hari Ini',
            'tomorrow' => 'Esok',
        ],
    ],

    'time' => [
        'helper' => 'Masa Sekarang',
    ],

    'step' => [
        'next' => 'Seterusnya',
        'previous' => 'Sebelumnya',
        'finish' => 'Selesai',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'KUNCI',
            'value' => 'NILAI',
        ],
        'placeholders' => [
            'key' => 'Masukkan kunci',
            'value' => 'Masukkan nilai',
        ],
        'add-row' => 'TAMBAH BARIS',
        'empty' => 'Tiada baris ditambah.',
    ],

    'currency' => [
        'symbol' => 'RM',
        'currency' => 'MYR',
    ],

    'list' => [
        'search' => 'Carian',
        'empty' => 'Tiada item.',
    ],

    'editor' => [
        'placeholder' => 'Mula menulis...',
        'tooltip' => [
            'style' => 'Gaya perenggan',
            'blockquote' => 'Petikan',
            'bold' => 'Tebal',
            'italic' => 'Condong',
            'underline' => 'Garis bawah',
            'strikethrough' => 'Garis lorek',
            'ordered_list' => 'Senarai bernombor',
            'unordered_list' => 'Senarai berbulet',
            'indent' => 'Tambah inden',
            'outdent' => 'Kurangkan inden',
            'align' => 'Penjajaran',
            'code' => 'Kod',
            'code_block' => 'Blok kod',
            'clear_format' => 'Kosongkan format',
            'link' => 'Sisip pautan',
            'image' => 'Sisip imej',
            'hr' => 'Garis melintang',
            'undo' => 'Buat asal',
            'redo' => 'Buat semula',
            'fullscreen' => 'Skrin penuh',
        ],
        'style' => [
            'paragraph' => 'Perenggan',
            'h1' => 'Tajuk 1',
            'h2' => 'Tajuk 2',
            'h3' => 'Tajuk 3',
        ],
        'align' => [
            'left' => 'Kiri',
            'center' => 'Tengah',
            'right' => 'Kanan',
            'justify' => 'Justifikasi',
        ],
        'image' => [
            'title' => 'Sisip imej',
            'upload' => 'Pilih fail',
            'upload_hint' => 'PNG, JPG, GIF atau WebP sehingga :size',
            'or_url' => 'atau tampal URL',
            'url' => 'URL',
            'alt' => 'Teks alternatif',
            'alt_hint' => 'Terangkan imej untuk pembaca skrin',
            'cancel' => 'Batal',
            'insert' => 'Sisip',
            'errors' => [
                'url' => 'URL tidak sah atau tidak boleh dicapai.',
                'mime' => 'Jenis fail tidak dibenarkan.',
                'size' => 'Fail melebihi had :max KB.',
                'failed' => 'Muat naik gagal. Sila cuba lagi.',
            ],
        ],
        'link' => [
            'title' => 'Sisip pautan',
            'text' => 'Teks',
            'url' => 'URL',
            'cancel' => 'Batal',
            'insert' => 'Sisip',
        ],
        'counters' => [
            'words' => ':count perkataan|:count perkataan',
            'lines' => ':count baris|:count baris',
        ],
    ],

    'spinner' => [
        'thinking' => 'Sedang berfikir...',
        'loading' => 'Memuatkan...',
    ],
];
