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

    'tag' => [
        'empty' => 'Sonuç bulunamadı',
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

    'qr-code' => [
        'copy' => 'Kopyala',
        'download' => 'İndir',
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

    'editor' => [
        'placeholder' => 'Yazmaya başlayın...',
        'tooltip' => [
            'style' => 'Paragraf stili',
            'blockquote' => 'Alıntı',
            'bold' => 'Kalın',
            'italic' => 'İtalik',
            'underline' => 'Altı çizili',
            'strikethrough' => 'Üstü çizili',
            'ordered_list' => 'Numaralı liste',
            'unordered_list' => 'Madde işaretli liste',
            'indent' => 'Girintiyi artır',
            'outdent' => 'Girintiyi azalt',
            'align' => 'Hizalama',
            'code' => 'Kod',
            'code_block' => 'Kod bloğu',
            'clear_format' => 'Biçimlendirmeyi temizle',
            'link' => 'Bağlantı ekle',
            'image' => 'Görsel ekle',
            'hr' => 'Yatay çizgi',
            'undo' => 'Geri al',
            'redo' => 'Yinele',
            'fullscreen' => 'Tam ekran',
        ],
        'style' => [
            'paragraph' => 'Paragraf',
            'h1' => 'Başlık 1',
            'h2' => 'Başlık 2',
            'h3' => 'Başlık 3',
        ],
        'align' => [
            'left' => 'Sola',
            'center' => 'Ortaya',
            'right' => 'Sağa',
            'justify' => 'İki yana',
        ],
        'image' => [
            'title' => 'Görsel ekle',
            'upload' => 'Dosya seç',
            'upload_hint' => 'PNG, JPG, GIF veya WebP, en fazla :size',
            'or_url' => 'ya da bir URL yapıştırın',
            'url' => 'URL',
            'alt' => 'Alternatif metin',
            'alt_hint' => 'Görseli ekran okuyucular için tanımlayın',
            'cancel' => 'İptal',
            'insert' => 'Ekle',
            'errors' => [
                'url' => 'Geçersiz veya erişilemeyen URL.',
                'mime' => 'Dosya türüne izin verilmiyor.',
                'size' => 'Dosya :max KB sınırını aşıyor.',
                'failed' => 'Yükleme başarısız. Lütfen tekrar deneyin.',
            ],
        ],
        'link' => [
            'title' => 'Bağlantı ekle',
            'text' => 'Metin',
            'url' => 'URL',
            'cancel' => 'İptal',
            'insert' => 'Ekle',
        ],
        'counters' => [
            'words' => ':count kelime|:count kelime',
            'lines' => ':count satır|:count satır',
        ],
    ],

    'spinner' => [
        'thinking' => 'Düşünüyor...',
        'loading' => 'Yükleniyor...',
    ],
];
