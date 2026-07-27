<?php

return [
    'environment' => [
        'environment' => 'Ortam',
        'branch' => 'Dal',
    ],

    'errors' => [
        'title' => ':count doğrulama hatası mevcut:',
    ],

    'select' => [
        'default' => 'Seçim yapınız',
        'search' => 'Arama yapın...',
        'empty' => 'Sonuç bulunamadı',
        'selected' => ':count seçildi',
    ],

    'autocomplete' => [
        'default' => 'Aramak için yazın...',
        'empty' => 'Sonuç bulunamadı',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Tamam',
            'confirm' => 'Onayla',
            'cancel' => 'Vazgeç',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Tamam',
            'confirm' => 'Onayla',
            'cancel' => 'Vazgeç',
        ],
    ],

    'command-palette' => [
        'search' => 'Ara...',
        'empty' => 'Sonuç bulunamadı.',
        'navigate' => 'gezin',
        'select' => 'seç',
        'close' => 'kapat',
    ],

    'table' => [
        'empty' => 'Sonuç bulunamadı.',
        'quantity' => 'Miktar',
        'search' => 'Arama yapın',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Kopyala',
            'copied' => 'Kopyalandı!',
        ],
    ],

    'password' => [
        'rules' => [
            'title' => 'Beklenen Şifre Formatı:',
            'formats' => [
                'min' => 'En az :min karakter',
                'numbers' => 'En az bir rakam',
                'symbols' => 'En az bir sembol (:symbols)',
                'mixed' => 'Büyük ve küçük harfler',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Bir dosya seçin',
        'size' => 'Boyut',
        'upload' => 'Yüklemek için buraya tıklayın',
        'uploaded' => [
            'single' => ':count dosya gönderildi',
            'multiple' => ':count dosya gönderildi',
        ],
        'error' => 'Bir şeyler yanlış gitti. Lütfen tekrar deneyin.',
        'static' => [
            'empty' => [
                'title' => 'Resim yok.',
                'description' => 'Henüz hiç resminiz yok.',
            ],
        ],
        'invalid' => 'Bir doğrulama hatası oluştu.',
    ],

    'upload_async' => [
        'title' => 'Dosyaları buraya bırakın',
        'description' => 'veya seçmek için tıklayın',
        'send' => 'Gönder',
        'clear' => 'Temizle',
        'ready' => [
            'single' => ':count dosya hazır · :size',
            'multiple' => ':count dosya hazır · :size',
        ],
        'errors' => [
            'mime' => 'Dosya türüne izin verilmiyor.',
            'size' => 'Dosya :max MB sınırını aşıyor.',
            'limit' => 'En fazla :max dosya yükleyebilirsiniz.',
            'network' => 'Ağ hatası. Lütfen tekrar deneyin.',
            'server' => 'Yükleme başarısız oldu. Lütfen tekrar deneyin.',
            'integrity' => 'Yükleme eksik olarak ulaştı. Lütfen tekrar deneyin.',
            'unauthorized' => 'Bu dosyayı yükleme izniniz yok.',
            'generic' => 'Bir şeyler yanlış gitti.',
        ],
        'remove' => 'Dosyayı kaldır',
        'preview' => [
            'close' => 'Önizlemeyi kapat',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Ocak',
                'february' => 'Şubat',
                'march' => 'Mart',
                'april' => 'Nisan',
                'may' => 'Mayıs',
                'june' => 'Haziran',
                'july' => 'Temmuz',
                'august' => 'Ağustos',
                'september' => 'Eylül',
                'october' => 'Ekim',
                'november' => 'Kasım',
                'december' => 'Aralık',
            ],
            'week' => [
                'sunday' => 'Pazar',
                'monday' => 'Pazartesi',
                'tuesday' => 'Salı',
                'wednesday' => 'Çarşamba',
                'thursday' => 'Perşembe',
                'friday' => 'Cuma',
                'saturday' => 'Cumartesi',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Dün',
            'today' => 'Bugün',
            'tomorrow' => 'Yarın',
        ],
    ],

    'time' => [
        'helper' => 'Geçerli Saat',
    ],

    'step' => [
        'next' => 'İleri',
        'previous' => 'Geri',
        'finish' => 'Bitir',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'ANAHTAR',
            'value' => 'DEĞER',
        ],
        'placeholders' => [
            'key' => 'Bir anahtar girin',
            'value' => 'Bir değer girin',
        ],
        'add-row' => 'SATIR EKLE',
        'empty' => 'Hiçbir satır eklenmedi.',
    ],

    'currency' => [
        'symbol' => '₺',
        'currency' => 'TRY',
    ],

    'list' => [
        'search' => 'Ara',
        'empty' => 'Öğe yok.',
    ],
];
