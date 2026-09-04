## Tổng quan
Plugin vận chuyển tiêu chuẩn cho GP247/Shop. Tính phí vận chuyển dựa trên tổng giá trị đơn hàng và tự động miễn phí khi đạt ngưỡng cấu hình.

- Yêu cầu: GP247/Shop (Core >= 3.0), gói `gp247/shop` đã cài đặt và hoạt động.
- Phiên bản: 2.3

## Giới thiệu chức năng
- Tính phí vận chuyển theo tổng tiền hàng.
- Miễn phí vận chuyển nếu tổng tiền đạt ngưỡng `shipping_free`.
- Cấu hình linh hoạt qua tệp `config.php` của plugin.
- **Cấu hình theo từng cửa hàng trên site nhiều cửa hàng / sàn**: mỗi cửa hàng tự bật plugin và có `fee` / ngưỡng miễn phí riêng, kế thừa cấu hình chung cho tới khi ghi đè (`storeScope: store`). Site một cửa hàng không bị ảnh hưởng.
- Cung cấp route front để kiểm tra hiển thị plugin và route admin để quản trị: `plugin/shippingstandard/index`, `GP247_ADMIN_PREFIX/shippingstandard`.

## Hướng dẫn cài đặt
Có thể cài đặt theo một trong các cách sau:

1) Cài đặt online
- Mở thư viện Plugin trong Admin.
- Tìm và cài đặt plugin ShippingStandard.

2) Cài đặt qua file .zip
- Tải gói .zip của plugin.
- Import gói .zip trong Admin để hệ thống cài đặt tự động.

3) Cài đặt thủ công (dành cho nhà phát triển)
- Giải nén gói plugin.
- Sao chép vào thư mục `app/GP247/Plugins/ShippingStandard`.
- Đăng nhập Admin, vào khu vực Extensions để kiểm tra và kích hoạt.

## Cách sử dụng
1) Kích hoạt plugin
- Vào Admin > Extensions > Shipping và bật plugin "ShippingStandard".

2) Cấu hình tham số
- Chỉnh trong tệp `app/GP247/Plugins/ShippingStandard/config.php`:

```php
<?php
return [
    'fee' => 20, // Base shipping fee
    'shipping_free' => 10000, // Free shipping threshold (subtotal >= value)
];
```

- Gợi ý cấu hình:
  - Đặt `shipping_free = 0` nếu muốn luôn tính phí (không áp dụng miễn phí).
  - Tăng/giảm `fee` để phù hợp chính sách vận chuyển.

3) Kiểm tra tích hợp
- Trong quy trình checkout, plugin tự tính phí dựa trên subtotal. Có thể truy cập `plugin/shippingstandard/index` để kiểm tra giao diện front của plugin.

## Tài liệu
- Link GitHub: `https://github.com/gp247net/ShippingStandard`
- Link hướng dẫn: `https://gp247.net/vi/docs/user-guide-extension/guide-to-installing-the-extension.html`

## Giấy phép
Được phát triển bởi GP247
