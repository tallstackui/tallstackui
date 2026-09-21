<?php

return [
    'environment' => [
        'environment' => 'البيئة',
        'branch' => 'الفرع',
    ],

    'errors' => [
        'title' => 'يوجد :count أخطاء في تحقق:',
    ],

    'select' => [
        'default' => 'إختر خيار',
        'search' => 'إبحث هنا',
        'empty' => 'لا توجد نتائج',
        'selected' => ':count محدد',
    ],

    'tag' => [
        'empty' => 'لا توجد نتائج',
    ],

    'autocomplete' => [
        'default' => 'اكتب للبحث...',
        'empty' => 'لا توجد نتائج',
    ],

    'toast' => [
        'button' => [
            'ok' => 'حسناً',
            'confirm' => 'تأكيد',
            'cancel' => 'إلغاء',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'حسناً',
            'confirm' => 'تأكيد',
            'cancel' => 'إلغاء',
        ],
    ],

    'command-palette' => [
        'search' => 'بحث...',
        'empty' => 'لا توجد نتائج.',
        'navigate' => 'تنقل',
        'select' => 'تحديد',
        'close' => 'إغلاق',
    ],

    'table' => [
        'empty' => 'لا توجد بيانات',
        'quantity' => 'عدد النتائج',
        'search' => 'إبحث هنا',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'نسخ',
            'copied' => 'تم النسخ!',
        ],
    ],

    'qr-code' => [
        'copy' => 'نسخ',
        'download' => 'تنزيل',
    ],

    'password' => [
        'rules' => [
            'title' => 'كلمة المرور يجب أن تحتوي على:',
            'formats' => [
                'min' => 'على الأقل :min حرف',
                'numbers' => 'على الأقل رقم واحد',
                'symbols' => 'على الأقل رمز واحد (:symbols)',
                'mixed' => 'على الأقل حرف كبير وحرف صغير',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'إختر ملف',
        'size' => 'الحجم',
        'upload' => 'إضغط هنا للرفع',
        'uploaded' => [
            'single' => 'تم رفع :count ملف',
            'multiple' => 'تم رفع :count ملفات',
        ],
        'error' => 'حدث خطأ ما, الرجاء المحاولة مرة أخرى',
        'static' => [
            'empty' => [
                'title' => 'لا توجد صور',
                'description' => 'لم يتم رفع أى ملفات حتى الآن',
            ],
        ],
        'invalid' => 'حدث خطأ في التحقق من الصحة.',
    ],

    'upload_async' => [
        'title' => 'أفلت الملفات هنا',
        'description' => 'أو اضغط للاختيار',
        'send' => 'إرسال',
        'clear' => 'مسح',
        'ready' => [
            'single' => ':count ملف جاهز · :size',
            'multiple' => ':count ملفات جاهزة · :size',
        ],
        'errors' => [
            'mime' => 'نوع الملف غير مسموح به.',
            'size' => 'الملف يتجاوز الحد الأقصى :max ميجابايت.',
            'limit' => 'يمكنك رفع :max ملفات كحد أقصى.',
            'network' => 'خطأ في الشبكة. الرجاء المحاولة مرة أخرى.',
            'server' => 'فشل الرفع. الرجاء المحاولة مرة أخرى.',
            'integrity' => 'وصل الملف المرفوع غير مكتمل. الرجاء المحاولة مرة أخرى.',
            'unauthorized' => 'غير مسموح لك برفع هذا الملف.',
            'generic' => 'حدث خطأ ما.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'يناير',
                'february' => 'فبراير',
                'march' => 'مارس',
                'april' => 'أبريل',
                'may' => 'مايو',
                'june' => 'يونيو',
                'july' => 'يوليو',
                'august' => 'أغسطس',
                'september' => 'سبتمبر',
                'october' => 'أكتوبر',
                'november' => 'نوفمبر',
                'december' => 'ديسمبر',
            ],
            'week' => [
                'sunday' => 'الأحد',
                'monday' => 'الإثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت',
            ],
        ],
        'helpers' => [
            'yesterday' => 'أمس',
            'today' => 'اليوم',
            'tomorrow' => 'غداً',
        ],
    ],

    'time' => [
        'helper' => 'الوقت الحالي',
    ],

    'step' => [
        'next' => 'التالي',
        'previous' => 'السابق',
        'finish' => 'إنهاء',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'المفتاح',
            'value' => 'القيمة',
        ],
        'placeholders' => [
            'key' => 'أدخل مفتاحًا',
            'value' => 'أدخل قيمة',
        ],
        'add-row' => 'إضافة صف',
        'empty' => 'لم تتم إضافة أي صفوف.',
    ],

    'currency' => [
        'symbol' => 'د.إ',
        'currency' => 'AED',
    ],

    'list' => [
        'search' => 'بحث',
        'empty' => 'لا توجد عناصر.',
    ],

    'editor' => [
        'placeholder' => 'ابدأ الكتابة...',
        'tooltip' => [
            'style' => 'نمط الفقرة',
            'blockquote' => 'اقتباس',
            'bold' => 'عريض',
            'italic' => 'مائل',
            'underline' => 'تسطير',
            'strikethrough' => 'يتوسطه خط',
            'ordered_list' => 'قائمة مرقمة',
            'unordered_list' => 'قائمة نقطية',
            'indent' => 'زيادة المسافة البادئة',
            'outdent' => 'تقليل المسافة البادئة',
            'align' => 'المحاذاة',
            'code' => 'كود',
            'code_block' => 'كتلة كود',
            'clear_format' => 'مسح التنسيق',
            'link' => 'إدراج رابط',
            'image' => 'إدراج صورة',
            'hr' => 'خط أفقي',
            'undo' => 'تراجع',
            'redo' => 'إعادة',
            'fullscreen' => 'ملء الشاشة',
        ],
        'style' => [
            'paragraph' => 'فقرة',
            'h1' => 'عنوان 1',
            'h2' => 'عنوان 2',
            'h3' => 'عنوان 3',
        ],
        'align' => [
            'left' => 'يسار',
            'center' => 'وسط',
            'right' => 'يمين',
            'justify' => 'ضبط',
        ],
        'image' => [
            'title' => 'إدراج صورة',
            'upload' => 'اختر ملفًا',
            'upload_hint' => 'PNG أو JPG أو GIF أو WebP حتى :size',
            'or_url' => 'أو الصق رابطًا',
            'url' => 'الرابط',
            'alt' => 'نص بديل',
            'alt_hint' => 'صف الصورة لقارئات الشاشة',
            'cancel' => 'إلغاء',
            'insert' => 'إدراج',
            'errors' => [
                'url' => 'رابط غير صالح أو غير متاح.',
                'mime' => 'نوع الملف غير مسموح به.',
                'size' => 'الملف يتجاوز حد :max كيلوبايت.',
                'failed' => 'فشل الرفع. حاول مرة أخرى.',
            ],
        ],
        'link' => [
            'title' => 'إدراج رابط',
            'text' => 'النص',
            'url' => 'الرابط',
            'cancel' => 'إلغاء',
            'insert' => 'إدراج',
        ],
        'counters' => [
            'words' => ':count كلمة|:count كلمة',
            'lines' => ':count سطر|:count سطر',
        ],
    ],

    'spinner' => [
        'thinking' => 'يفكر...',
        'loading' => 'جار التحميل...',
    ],
];
