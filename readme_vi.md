## Tổng quan
Plugin vận chuyển tiêu chuẩn cho GP247/Shop. Tính phí vận chuyển dựa trên tổng giá trị đơn hàng và tự động miễn phí khi đạt ngưỡng cấu hình.

- Yêu cầu: GP247/Shop (Core 3.0 hoặc 3.1), gói `gp247/shop` đã cài đặt và hoạt động.
- Phiên bản: 2.4.1

## Giới thiệu chức năng
- Tính phí vận chuyển theo tổng tiền hàng.
- Miễn phí vận chuyển nếu tổng tiền đạt ngưỡng `shipping_free`.
- Cấu hình linh hoạt qua tệp `config.php` của plugin.
- **Cấu hình theo từng cửa hàng trên site nhiều cửa hàng / sàn**: mỗi cửa hàng tự bật plugin và có `fee` / ngưỡng miễn phí riêng, kế thừa cấu hình chung cho tới khi ghi đè (`storeScope: store`). Site một cửa hàng không bị ảnh hưởng.
- **Chạy trên lớp vận chuyển chuẩn của `gp247/shop`** (từ 2.4.0, với bản shop có lớp này): phí được báo giá cùng các hãng khác ở bước vận chuyển, giữ nguyên khi khách đặt hàng và quy đổi đúng theo tiền tệ đang hiển thị. Với `gp247/shop` cũ hơn, plugin chạy như trước.
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

4) Cài bằng dòng lệnh (CLI, gp247 3.x)

Từ gp247 3.x, bạn có thể tải **ShippingStandard** từ thư viện GP247 và cài ngay bằng dòng lệnh mà không cần mở admin. Mở Terminal tại thư mục gốc website rồi chạy:

```bash
# 1) Chỉ làm 1 lần cho mỗi website: đăng ký API License (miễn phí) để kết nối thư viện GP247
php artisan gp247:ext-register-license

# 2) Tải plugin từ thư viện và cài
php artisan gp247:ext-install --type=plugin --key=ShippingStandard
```

- Trước bước 1, kiểm tra `APP_URL` trong `.env` là **domain thật** của website (không để `http://localhost`), vì license được gắn với domain này.
- Cài xong, plugin được **bật sẵn** và cache tự làm mới, bạn không cần thao tác gì thêm trong admin.
- Lệnh tự kiểm tra điều kiện khai báo trong `gp247.json` (phiên bản core, gói composer, plugin phụ thuộc). Nếu thiếu, lệnh dừng lại và báo rõ thiếu gì.
- Plugin này cần gói `gp247/shop` đã được cài; nếu thiếu, lệnh sẽ dừng lại và báo.
- Nếu thư mục `app/GP247/Plugins/ShippingStandard` đã có sẵn trên máy (chép thủ công hoặc có sẵn theo bộ cài), lệnh sẽ **cài tại chỗ**, không tải lại.
- Nếu plugin đã được cài, lệnh sẽ từ chối. Để lên bản mới, chạy `php artisan gp247:ext-update --type=plugin --key=ShippingStandard`.
- Thêm `--json` vào cuối lệnh để nhận kết quả dạng máy đọc được (dùng cho script/CI).
- Các bước ở mục **Cách sử dụng** bên dưới vẫn giữ nguyên: bước 1 (kích hoạt) đã được lệnh làm sẵn, còn bước 2 (cấu hình `fee` / `shipping_free`) và bước 3 (kiểm tra tích hợp) bạn vẫn cần làm.
- Chi tiết: [Hướng dẫn cài đặt Plugin & Template](https://github.com/gp247net/gp247-docs/blob/main/extension/install-extension_vi.md) · [Tra cứu lệnh](https://github.com/gp247net/gp247-docs/blob/main/system/command-line-reference_vi.md).

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
