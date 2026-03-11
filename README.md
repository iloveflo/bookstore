# 📚 Ứng dụng Website Bán Sách – PHP & MySQL

Đây là một dự án website bán sách được xây dựng bằng **PHP thuần**, **MySQL**, sử dụng kiến trúc **MVC (Model - View - Controller)**. Người dùng có thể duyệt sách, đặt hàng, quản lý tài khoản, và nhiều chức năng khác như một website thương mại điện tử thực thụ.

---

## 🚀 Tính năng nổi bật

- 🛒 Xem, tìm kiếm và xem chi tiết sách
- 👤 Đăng ký, đăng nhập, chỉnh sửa thông tin tài khoản
- 📦 Tạo đơn hàng và xem lịch sử đơn hàng
- 🧑‍💼 Quản lý sách và danh mục (dành cho admin)
- 💬 Bình luận, đánh giá sách
- 🔐 Quản lý phiên đăng nhập an toàn
- 🌐 Cấu trúc rõ ràng theo mô hình MVC

---

## 🗂️ Cấu trúc thư mục dự án

```
bookstore/
├── app/
│   ├── Controllers/       # Điều khiển logic ứng dụng
│   ├── Models/            # Kết nối và xử lý dữ liệu từ CSDL
│   ├── functions.php      # Các hàm dùng chung
│   └── SessionGuard.php   # Quản lý phiên đăng nhập
├── public/                # Tài nguyên công khai (giao diện chính)
├── views/                 # Giao diện người dùng
├── bootstrap.php          # Tập tin khởi tạo và định tuyến
├── composer.json          # Quản lý thư viện PHP
├── bookstore.sql          # Tập tin khởi tạo CSDL
```

---

## ⚙️ Hướng dẫn cài đặt và chạy thử

1. **Tải mã nguồn**

   ```bash
   git clone https://github.com/ten-cua-ban/bookstore.git
   ```

2. **Khởi tạo cơ sở dữ liệu**

   - Mở **phpMyAdmin**
   - Tạo một CSDL mới, ví dụ: `bookstore`
   - Import file `bookstore.sql` vào CSDL

3. **Cấu hình kết nối**

   - Sao chép file `.env.example` thành `.env`
   - Chỉnh sửa thông tin kết nối DB, URL,…

4. **Cài đặt thư viện PHP (Composer)**

   ```bash
   composer install
   ```

5. **Chạy ứng dụng trên localhost**

   ```bash
   php -S localhost:8000 -t public
   ```

6. **Truy cập trình duyệt**
   ```
   http://localhost/bookstore/public/
   ```

---

## 🧪 Tài khoản

- Admin: `admin@gmail.com` / `123123`
- Người dùng: `Tùy chỉnh trong đăng ký`

---

## 💡 Công nghệ sử dụng

- PHP (thuần)
- MySQL
- Composer
- HTML/CSS/JS (Giao diện)
- Mô hình MVC

---

## 📬 Liên hệ

Mọi góp ý hoặc thắc mắc xin vui lòng liên hệ qua GitHub hoặc email: **Nhom11PHP@gmail.com**

---

## 📄 Giấy phép

Dự án này được phát hành mã nguồn mở theo giấy phép [MIT License](LICENSE).
