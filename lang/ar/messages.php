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
        'remove' => 'إزالة الملف',
        'preview' => [
            'close' => 'إغلاق المعاينة',
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
];
