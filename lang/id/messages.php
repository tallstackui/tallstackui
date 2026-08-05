<?php

return [
    'environment' => [
        'environment' => 'Lingkungan',
        'branch' => 'Cabang',
    ],

    'errors' => [
        'title' => 'Terdapat :count kesalahan validasi:',
    ],

    'select' => [
        'default' => 'Pilih opsi',
        'search' => 'Cari sesuatu di sini',
        'empty' => 'Tidak ada hasil ditemukan',
        'selected' => ':count dipilih',
    ],

    'tag' => [
        'empty' => 'Tidak ada hasil ditemukan',
    ],

    'autocomplete' => [
        'default' => 'Ketik untuk mencari...',
        'empty' => 'Tidak ada hasil ditemukan',
    ],

    'toast' => [
        'button' => [
            'ok' => 'OK',
            'confirm' => 'Konfirmasi',
            'cancel' => 'Batal',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'OK',
            'confirm' => 'Konfirmasi',
            'cancel' => 'Batal',
        ],
    ],

    'command-palette' => [
        'search' => 'Cari...',
        'empty' => 'Tidak ada hasil ditemukan.',
        'navigate' => 'navigasi',
        'select' => 'pilih',
        'close' => 'tutup',
    ],

    'table' => [
        'empty' => 'Tidak ada hasil ditemukan.',
        'quantity' => 'Jumlah',
        'search' => 'Cari sesuatu di sini',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Salin',
            'copied' => 'Tersalin!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Salin',
        'download' => 'Unduh',
    ],

    'password' => [
        'rules' => [
            'title' => 'Format Kata Sandi yang Diharapkan:',
            'formats' => [
                'min' => 'Minimal :min karakter',
                'numbers' => 'Minimal satu angka',
                'symbols' => 'Minimal satu simbol (:symbols)',
                'mixed' => 'Huruf besar dan kecil',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Pilih file',
        'size' => 'Ukuran',
        'upload' => 'Klik di sini untuk mengunggah',
        'uploaded' => [
            'single' => ':count file terunggah',
            'multiple' => ':count file terunggah',
        ],
        'error' => 'Terjadi kesalahan. Silakan coba lagi.',
        'static' => [
            'empty' => [
                'title' => 'Tidak ada gambar.',
                'description' => 'Anda belum memiliki gambar.',
            ],
        ],
        'invalid' => 'Terjadi kesalahan validasi.',
    ],

    'upload_async' => [
        'title' => 'Letakkan file di sini',
        'description' => 'atau klik untuk memilih',
        'send' => 'Kirim',
        'clear' => 'Bersihkan',
        'ready' => [
            'single' => ':count file siap · :size',
            'multiple' => ':count file siap · :size',
        ],
        'errors' => [
            'mime' => 'Tipe file tidak diizinkan.',
            'size' => 'File melebihi batas :max MB.',
            'limit' => 'Anda hanya dapat mengunggah maksimal :max file.',
            'network' => 'Kesalahan jaringan. Silakan coba lagi.',
            'server' => 'Gagal mengunggah. Silakan coba lagi.',
            'integrity' => 'Unggahan diterima tidak lengkap. Silakan coba lagi.',
            'unauthorized' => 'Anda tidak diizinkan mengunggah file ini.',
            'generic' => 'Terjadi kesalahan.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Januari',
                'february' => 'Februari',
                'march' => 'Maret',
                'april' => 'April',
                'may' => 'Mei',
                'june' => 'Juni',
                'july' => 'Juli',
                'august' => 'Agustus',
                'september' => 'September',
                'october' => 'Oktober',
                'november' => 'November',
                'december' => 'Desember',
            ],
            'week' => [
                'sunday' => 'Minggu',
                'monday' => 'Senin',
                'tuesday' => 'Selasa',
                'wednesday' => 'Rabu',
                'thursday' => 'Kamis',
                'friday' => 'Jumat',
                'saturday' => 'Sabtu',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Kemarin',
            'today' => 'Hari ini',
            'tomorrow' => 'Besok',
        ],
    ],

    'time' => [
        'helper' => 'Waktu Saat Ini',
    ],

    'step' => [
        'next' => 'Selanjutnya',
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
        'empty' => 'Tidak ada baris ditambahkan.',
    ],

    'currency' => [
        'symbol' => 'Rp',
        'currency' => 'IDR',
    ],

    'list' => [
        'search' => 'Cari',
        'empty' => 'Tidak ada item.',
    ],

    'editor' => [
        'placeholder' => 'Mulai menulis...',
        'tooltip' => [
            'style' => 'Gaya paragraf',
            'blockquote' => 'Kutipan',
            'bold' => 'Tebal',
            'italic' => 'Miring',
            'underline' => 'Garis bawah',
            'strikethrough' => 'Coret',
            'ordered_list' => 'Daftar bernomor',
            'unordered_list' => 'Daftar berpoin',
            'indent' => 'Tambah indentasi',
            'outdent' => 'Kurangi indentasi',
            'align' => 'Perataan',
            'code' => 'Kode',
            'code_block' => 'Blok kode',
            'clear_format' => 'Hapus format',
            'link' => 'Sisipkan tautan',
            'image' => 'Sisipkan gambar',
            'hr' => 'Garis horizontal',
            'undo' => 'Urungkan',
            'redo' => 'Ulangi',
            'fullscreen' => 'Layar penuh',
        ],
        'style' => [
            'paragraph' => 'Paragraf',
            'h1' => 'Judul 1',
            'h2' => 'Judul 2',
            'h3' => 'Judul 3',
        ],
        'align' => [
            'left' => 'Kiri',
            'center' => 'Tengah',
            'right' => 'Kanan',
            'justify' => 'Rata kanan-kiri',
        ],
        'image' => [
            'title' => 'Sisipkan gambar',
            'upload' => 'Pilih berkas',
            'upload_hint' => 'PNG, JPG, GIF atau WebP hingga :size',
            'or_url' => 'atau tempel URL',
            'url' => 'URL',
            'alt' => 'Teks alternatif',
            'alt_hint' => 'Jelaskan gambar untuk pembaca layar',
            'cancel' => 'Batal',
            'insert' => 'Sisipkan',
            'errors' => [
                'url' => 'URL tidak valid atau tidak dapat diakses.',
                'mime' => 'Tipe berkas tidak diizinkan.',
                'size' => 'Berkas melebihi batas :max KB.',
                'failed' => 'Gagal mengunggah. Silakan coba lagi.',
            ],
        ],
        'link' => [
            'title' => 'Sisipkan tautan',
            'text' => 'Teks',
            'url' => 'URL',
            'cancel' => 'Batal',
            'insert' => 'Sisipkan',
        ],
        'counters' => [
            'words' => ':count kata|:count kata',
            'lines' => ':count baris|:count baris',
        ],
    ],

    'spinner' => [
        'thinking' => 'Berpikir...',
        'loading' => 'Memuat...',
    ],
];
