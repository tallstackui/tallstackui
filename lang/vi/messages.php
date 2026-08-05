<?php

return [
    'environment' => [
        'environment' => 'Môi trường',
        'branch' => 'Nhánh',
    ],

    'errors' => [
        'title' => 'Có :count lỗi xác thực:',
    ],

    'select' => [
        'default' => 'Chọn một tùy chọn',
        'search' => 'Tìm kiếm ở đây',
        'empty' => 'Không tìm thấy kết quả',
        'selected' => ':count đã chọn',
    ],

    'tag' => [
        'empty' => 'Không tìm thấy kết quả',
    ],

    'autocomplete' => [
        'default' => 'Nhập để tìm kiếm...',
        'empty' => 'Không tìm thấy kết quả',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Xác nhận',
            'cancel' => 'Hủy',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Xác nhận',
            'cancel' => 'Hủy',
        ],
    ],

    'command-palette' => [
        'search' => 'Tìm kiếm...',
        'empty' => 'Không tìm thấy kết quả.',
        'navigate' => 'điều hướng',
        'select' => 'chọn',
        'close' => 'đóng',
    ],

    'table' => [
        'empty' => 'Không tìm thấy kết quả.',
        'quantity' => 'Số lượng',
        'search' => 'Tìm kiếm ở đây',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Sao chép',
            'copied' => 'Đã sao chép!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Sao chép',
        'download' => 'Tải xuống',
    ],

    'password' => [
        'rules' => [
            'title' => 'Định dạng mật khẩu mong đợi:',
            'formats' => [
                'min' => 'Ít nhất :min ký tự',
                'numbers' => 'Ít nhất một số',
                'symbols' => 'Ít nhất một ký tự đặc biệt (:symbols)',
                'mixed' => 'Chữ hoa và chữ thường',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Chọn một tệp',
        'size' => 'Kích thước',
        'upload' => 'Nhấp vào đây để tải lên',
        'uploaded' => [
            'single' => ':count tệp đã gửi',
            'multiple' => ':count tệp đã gửi',
        ],
        'error' => 'Đã xảy ra lỗi. Vui lòng thử lại.',
        'static' => [
            'empty' => [
                'title' => 'Không có hình ảnh.',
                'description' => 'Bạn chưa có bất kỳ hình ảnh nào.',
            ],
        ],
        'invalid' => 'Đã xảy ra lỗi xác thực.',
    ],

    'upload_async' => [
        'title' => 'Thả tệp vào đây',
        'description' => 'hoặc nhấp để chọn',
        'send' => 'Gửi',
        'clear' => 'Xóa',
        'ready' => [
            'single' => ':count tệp sẵn sàng · :size',
            'multiple' => ':count tệp sẵn sàng · :size',
        ],
        'errors' => [
            'mime' => 'Loại tệp không được phép.',
            'size' => 'Tệp vượt quá giới hạn :max MB.',
            'limit' => 'Bạn chỉ có thể tải lên tối đa :max tệp.',
            'network' => 'Lỗi mạng. Vui lòng thử lại.',
            'server' => 'Tải lên thất bại. Vui lòng thử lại.',
            'integrity' => 'Tệp tải lên bị thiếu dữ liệu. Vui lòng thử lại.',
            'unauthorized' => 'Bạn không được phép tải lên tệp này.',
            'generic' => 'Đã xảy ra lỗi.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Tháng Một',
                'february' => 'Tháng Hai',
                'march' => 'Tháng Ba',
                'april' => 'Tháng Tư',
                'may' => 'Tháng Năm',
                'june' => 'Tháng Sáu',
                'july' => 'Tháng Bảy',
                'august' => 'Tháng Tám',
                'september' => 'Tháng Chín',
                'october' => 'Tháng Mười',
                'november' => 'Tháng Mười Một',
                'december' => 'Tháng Mười Hai',
            ],
            'week' => [
                'sunday' => 'Chủ Nhật',
                'monday' => 'Thứ Hai',
                'tuesday' => 'Thứ Ba',
                'wednesday' => 'Thứ Tư',
                'thursday' => 'Thứ Năm',
                'friday' => 'Thứ Sáu',
                'saturday' => 'Thứ Bảy',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Hôm qua',
            'today' => 'Hôm nay',
            'tomorrow' => 'Ngày mai',
        ],
    ],

    'time' => [
        'helper' => 'Thời gian hiện tại',
    ],

    'step' => [
        'next' => 'Tiếp theo',
        'previous' => 'Trước đó',
        'finish' => 'Hoàn thành',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'KHÓA',
            'value' => 'GIÁ TRỊ',
        ],
        'placeholders' => [
            'key' => 'Nhập khóa',
            'value' => 'Nhập giá trị',
        ],
        'add-row' => 'THÊM DÒNG',
        'empty' => 'Chưa thêm dòng nào.',
    ],

    'currency' => [
        'symbol' => '₫',
        'currency' => 'VND',
    ],

    'list' => [
        'search' => 'Tìm kiếm',
        'empty' => 'Không có mục nào.',
    ],

    'editor' => [
        'placeholder' => 'Bắt đầu viết...',
        'tooltip' => [
            'style' => 'Kiểu đoạn văn',
            'blockquote' => 'Trích dẫn',
            'bold' => 'Đậm',
            'italic' => 'Nghiêng',
            'underline' => 'Gạch chân',
            'strikethrough' => 'Gạch ngang',
            'ordered_list' => 'Danh sách đánh số',
            'unordered_list' => 'Danh sách dấu đầu dòng',
            'indent' => 'Tăng thụt lề',
            'outdent' => 'Giảm thụt lề',
            'align' => 'Căn lề',
            'code' => 'Mã',
            'code_block' => 'Khối mã',
            'clear_format' => 'Xóa định dạng',
            'link' => 'Chèn liên kết',
            'image' => 'Chèn hình ảnh',
            'hr' => 'Đường kẻ ngang',
            'undo' => 'Hoàn tác',
            'redo' => 'Làm lại',
            'fullscreen' => 'Toàn màn hình',
        ],
        'style' => [
            'paragraph' => 'Đoạn văn',
            'h1' => 'Tiêu đề 1',
            'h2' => 'Tiêu đề 2',
            'h3' => 'Tiêu đề 3',
        ],
        'align' => [
            'left' => 'Trái',
            'center' => 'Giữa',
            'right' => 'Phải',
            'justify' => 'Đều hai bên',
        ],
        'image' => [
            'title' => 'Chèn hình ảnh',
            'upload' => 'Chọn tệp',
            'upload_hint' => 'PNG, JPG, GIF hoặc WebP tối đa :size',
            'or_url' => 'hoặc dán một URL',
            'url' => 'URL',
            'alt' => 'Văn bản thay thế',
            'alt_hint' => 'Mô tả hình ảnh cho trình đọc màn hình',
            'cancel' => 'Hủy',
            'insert' => 'Chèn',
            'errors' => [
                'url' => 'URL không hợp lệ hoặc không truy cập được.',
                'mime' => 'Loại tệp không được phép.',
                'size' => 'Tệp vượt quá giới hạn :max KB.',
                'failed' => 'Tải lên thất bại. Vui lòng thử lại.',
            ],
        ],
        'link' => [
            'title' => 'Chèn liên kết',
            'text' => 'Văn bản',
            'url' => 'URL',
            'cancel' => 'Hủy',
            'insert' => 'Chèn',
        ],
        'counters' => [
            'words' => ':count từ|:count từ',
            'lines' => ':count dòng|:count dòng',
        ],
    ],

    'spinner' => [
        'thinking' => 'Đang suy nghĩ...',
        'loading' => 'Đang tải...',
    ],
];
