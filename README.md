<div align="center">
  <h1>📚 BookStore</h1>
  <p>Một ứng dụng quản lý cửa hàng sách chuyên nghiệp, được xây dựng bằng PHP thuần theo kiến trúc MVC tự định nghĩa (Custom MVC Architecture). Dự án kết hợp Eloquent ORM, Bramus Router, Plates Templating và Docker để mang lại trải nghiệm phát triển/triển khai đơn giản nhưng chuẩn chỉ.</p>
  <strong>⚠️ Lưu ý: Dự án phục vụ mục đích học tập, không phải sản phẩm thương mại.</strong>
</div>

---

## 📖 Bối cảnh & Mục tiêu dự án (Background & Purpose)

**BookStore** được thiết kế như một ví dụ thực chiến cho việc xây dựng Web Application hoàn chỉnh bằng PHP thuần (Vanilla PHP) nhưng vẫn tuân thủ các tiêu chuẩn thiết kế hiện đại.

Thay vì dùng framework lớn (Laravel, Symfony), dự án ghép các "mảnh ghép" rời rạc thông qua Composer, giúp:
- Hiểu rõ vòng đời Request (Request Lifecycle).
- Nắm cơ chế Autoloading chuẩn PSR-4.
- Thực hành ORM (Object-Relational Mapping) qua `illuminate/database`.
- Tách bạch rõ ràng Controller / View / Model trong kiến trúc MVC.

---

## 🚀 Các tính năng nổi bật (Key Features)

Dự án bao phủ đầy đủ luồng của một cửa hàng sách online quy mô nhỏ:

1. **🛍️ Quản lý sản phẩm (Sách) & Phân loại (Danh mục)**
   - Danh sách đầu sách với giao diện trực quan.
   - Phân loại sách theo danh mục (Văn học, Khoa học, IT, ...).
   - Tìm kiếm, lọc và phân trang (Pagination).
   - Xem chi tiết sách: tác giả, NXB, giá, mô tả.

2. **🛒 Giỏ hàng & Thanh toán (Shopping Cart & Checkout)**
   - Thêm sách vào giỏ (yêu cầu đăng nhập).
   - Cập nhật số lượng, xóa sản phẩm khỏi giỏ.
   - Kiểm tra tồn kho khi thêm/cộng dồn sản phẩm.
   - Đặt hàng (Checkout) với số điện thoại, địa chỉ giao hàng.
   - Tự động cập nhật tồn kho và lượt bán (`sold`) sau khi đặt hàng.

3. **📦 Quản lý Đơn hàng / Hóa đơn (Order & Bill Management)**
   - Xem lịch sử mua hàng (Payment History) theo thời gian.
   - Xem chi tiết từng hóa đơn (sản phẩm, số lượng, tổng tiền).
   - Cho phép **Hủy đơn** hoặc **Xác nhận đã nhận hàng**.
   - Theo dõi trạng thái: Processing → Sending → Received / Canceled.

4. **📝 Hệ thống Blog / Bài viết (Blog & Articles)**
   - Trang Blog với danh sách bài viết `published`, có phân trang.
   - Tìm kiếm bài viết theo tiêu đề.
   - Xem chi tiết bài viết kèm thông tin tác giả (Admin).
   - Gợi ý 3 bài viết liên quan mới nhất ở cuối mỗi bài.

5. **🔐 Xác thực & Phân quyền (Authentication & Authorization)**
   - Đăng nhập / Đăng ký người dùng thường.
   - Quản lý phiên đăng nhập an toàn qua lớp `SessionGuard`.
   - Quên mật khẩu: sinh token reset, gửi link khôi phục qua email thực (`PHPMailer` + SMTP).
   - Quản lý thông tin cá nhân: cập nhật hồ sơ, đổi mật khẩu.
   - (Tùy chọn) Tích hợp OAuth2 (Google/Facebook) nếu bạn cấu hình `.env` phù hợp.

6. **📊 Dashboard phân tích (Admin Analytics Dashboard)**
   - Tổng quan: doanh thu, đơn hàng, sản phẩm, người dùng.
   - Biểu đồ doanh thu theo mốc: Hôm nay / Tháng / Quý / Năm.
   - Top 5 sản phẩm bán chạy.
   - Doanh số theo loại sách (theo danh mục).
   - Phân bổ trạng thái đơn hàng.
   - Thống kê phương thức thanh toán (COD / Chuyển khoản).

7. **🛠️ Trang Quản trị Admin (Admin Management Panel)**
   - **Sản phẩm**: Thêm / Sửa / Xóa, sắp xếp và lọc.
   - **Đơn hàng**: Xem, duyệt, hủy, cập nhật trạng thái giao hàng.
   - **Bài viết**: CRUD bài Blog với trạng thái `published/draft`.
   - **Người dùng**: Danh sách, chi tiết và cập nhật thông tin.

8. **🏗️ Kiến trúc & Mã nguồn**
   - **Custom MVC**: Cấu trúc rõ ràng, dễ mở rộng.
   - **Routing** với `bramus/router`: URL thân thiện, hỗ trợ prefix/middleware.
   - **Templating** với `league/plates`: layout, kế thừa view tương tự Blade.
   - **ORM Eloquent** (`illuminate/database`): Relationship 1-n, n-n, hạn chế SQL Injection.

9. **🐳 Container hóa với Docker**
   - Cấu hình sẵn `docker-compose.yml`.
   - 2 services chính:
     - 🖥️ **app**: Nginx + PHP-FPM (container `bookstore_app`, map `8000:80`).
     - 🐬 **db**: MySQL 8.0 (container `bookstore_db`, map `3307:3306`).
   - Tự động import dữ liệu mẫu từ `bookstore.sql` ở lần chạy đầu.

---

## 🛠 Công nghệ sử dụng (Technology Stack)

### Backend 
- **Ngôn ngữ:** [PHP 8.x+](https://www.php.net/)
- **Dependency Manager:** [Composer](https://getcomposer.org/)
- **Routing:** [bramus/router](https://github.com/bramus/router) `^1.6`
- **Templating Engine:** [league/plates](https://platesphp.com/) `3.*`
- **ORM / Database:** [illuminate/database](https://github.com/illuminate/database) `^11.0`
- **Environment:** [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) `^5.4`
- **Mailing:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) `^7.0`

### Infrastructure & DevOps
- **Docker** & **Docker Compose**
- **Nginx** (Alpine)
- **MySQL 8.0**

### 🚀 CI/CD Pipeline & Triển khai
- **GitHub Actions:** Workflow `.github/workflows/docker-image.yml` tự động build & push Docker image mỗi khi push lên nhánh `main` (trừ các file `.md`, `.txt`, `.gitignore`, chính file workflow).
- **Docker Hub:** Image được push với tag: `${DOCKER_USERNAME}/bookstore:tagname` (thiết lập qua `secrets`).
- **Render / Cloud khác:** Trigger deploy thông qua `RENDER_DEPLOY_HOOK` (HTTP POST), giúp tự động cập nhật môi trường production.
- **MySQL Managed (ví dụ: Aiven):** Có thể dùng cloud DB để tách biệt database khỏi server app.

---

## 📁 Cấu trúc thư mục chi tiết (Deep Dive into Folder Structure)

Để dễ dàng nắm bắt, dưới đây là giải thích luồng hoạt động thông qua cấu trúc thư mục:

```text
BookWorm/
├── app/                        # (BỘ NÃO) Khu vực chứa mã PHP chính yếu
│   ├── Controllers/            # Chứa các Class điều khiển luồng
│   │   ├── Auth/               # Nhóm Controller xử lý Đăng nhập/Đăng ký/Quên mật khẩu
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   └── ForgotPasswordController.php
│   │   ├── Manage/             # Nhóm Controller quản trị Admin
│   │   │   └── ManagementController.php  # CRUD sản phẩm, đơn hàng, bài viết, user
│   │   ├── HomeController.php  # Trang chủ, About, Search
│   │   ├── ProductController.php # Hiển thị sản phẩm, chi tiết, phân loại
│   │   ├── CartController.php  # Giỏ hàng & Thanh toán
│   │   ├── BillController.php  # Lịch sử đơn hàng, hủy/xác nhận
│   │   ├── ArticleController.php # Blog / Bài viết
│   │   └── DashboardController.php # Dashboard phân tích dữ liệu
│   ├── Models/                 # Các Class Eloquent, đại diện cho các bảng trong DB
│   │   ├── Product.php         # Sách
│   │   ├── ProductType.php     # Loại sách
│   │   ├── User.php            # Người dùng
│   │   ├── Cart.php            # Giỏ hàng
│   │   ├── Bill.php            # Hóa đơn
│   │   ├── BillDetail.php      # Chi tiết hóa đơn
│   │   ├── Article.php         # Bài viết Blog
│   │   ├── TacGia.php          # Tác giả
│   │   └── NhaXuatBan.php      # Nhà xuất bản
│   ├── SessionGuard.php        # Lớp Custom bảo vệ, quản lý Authentication State
│   └── functions.php           # Global Helper functions (các hàm hỗ trợ dùng ở mọi nơi)
├── config/                     # Cấu hình phụ (nếu thiết lập mở rộng)
├── docker/               
│   ├── Dockerfile              # Các bước setup Image cho PHP container
│   └── nginx/
│       └── default.conf        # Cấu hình Nginx thiết lập DocumentRoot trỏ vào /public
├── public/                     # (CỬA VÀO - ENTRY POINT) Chỉ thư mục này public ra web
│   ├── css/, js/, img/, fonts/ # Chứa CSS, JavaScript, Images, Fonts tĩnh
│   └── index.php               # Tệp DUY NHẤT nhận Request -> Nạp Bootstrap -> Route
├── vendor/                     # (THƯ VIỆN) Chứa toàn bộ package của Composer (không đẩy lên Git)
├── views/                      # (GIAO DIỆN) Nơi chứa các tệp HTML kết hợp PHP (Plates logic)
│   ├── layouts/                # Bố cục chung (Header, Footer, Search, Default layout)
│   ├── auth/                   # Giao diện Đăng nhập, Đăng ký, Quên mật khẩu
│   ├── products/               # Danh sách sản phẩm, Chi tiết sản phẩm
│   ├── cart/                   # Giỏ hàng, Trang thanh toán thành công
│   ├── bill/                   # Lịch sử mua hàng, Chi tiết hóa đơn
│   ├── blog/                   # Danh sách bài viết, Chi tiết bài viết
│   ├── about/                  # Trang giới thiệu
│   ├── manage/                 # Trang quản trị Admin (Dashboard, CRUD sản phẩm/đơn hàng/bài viết/user)
│   └── home.php                # Trang chủ
├── .env                        # Lưu trữ biến môi trường (Credentials, Mail Config...)
├── bootstrap.php               # Tệp cực quan trọng khởi động Core: load autoload, thiết lập DB Manager
├── composer.json               # Khai báo dependency và rule chạy chuẩn PSR-4 Autoload cho thư mục 'app'
├── docker-compose.yml          # Trái tim của toàn bộ lệnh Docker Orchestration
├── server.php                  # Script hỗ trợ chạy PHP built-in server (dev không cần Docker)
└── bookstore.sql               # Snapshot Database ban đầu, Docker sẽ auto-import file này
```

---

## ⚙️ Hướng dẫn Cài đặt & Khởi chạy (Step-by-Step Installation)

Bạn có thể chạy dự án này trên môi trường Local như XAMPP/Laragon. Tuy nhiên, cách **được khuyến nghị** mạnh mẽ nhất và tránh lỗi vặt (nhất là cấu hình PHP version hay Extension) là sử dụng **Docker**.

### Yêu cầu hệ thống trước khi bắt đầu (Prerequisites):
- Đã cài đặt [Git](https://git-scm.com/)
- Đã có Docker Desktop hoặc [Docker Engine + Docker Compose](https://docs.docker.com/engine/install/)

### Bước 1: Clone kho lưu trữ (Clone the repository)
Mở Terminal / Command Prompt và chạy:
```bash
git clone https://github.com/iloveflo/bookworm.git
cd bookworm
```

### Bước 2: Chuẩn bị biến môi trường (Setup Environment)
Tài liệu cung cấp sẵn file `.env` mẫu để chạy Docker, nhưng nếu hệ thống chưa có, bạn tạo file `.env` ở thư mục gốc:

```ini
APP_NAME=BookWorm
APP_URL=http://localhost:8000

# Thiết lập Database trong Container
DB_HOST=db
DB_NAME=bookstore_db
DB_USER=root
DB_PASS=rootpassword
DB_PORT=3306

# SMTP Cấu hình (Nếu muốn test luồng Đăng ký/Quên mật khẩu thực tế)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-app-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
```

### Bước 3: Ráp hình Container (Build & Up services)
Tại thư mục chứa dự án. Hãy gọi thần chú Docker:
```bash
docker-compose up -d --build
```
*Ghi chú: Lệnh này thiết lập 3 containers. Database `bookstore_db` sẽ tự động được tạo và bơm dữ liệu mồi từ lệnh sql trong `bookstore.sql`.*

### Bước 4: Lên đồ cho Vendor (Install Composer Dependencies)
Bên trong mã nguồn đang thiếu thư mục `/vendor/` vì nó bị loại trừ bởi `.gitignore`. Ta cần cài đặt chúng trực tiếp từ trong Container PHP:
```bash
# 1. Đi vào trong hầm (Terminal của PHP container)
docker exec -it bookstore_app sh

# 2. Cài đặt các Package được khai báo
composer install

# 3. Trở ra ngoài
exit
```

### Bước 5: Thưởng thức thành quả (Enjoy!)
Hãy mở trình duyệt bất kỳ của bạn lên, và truy cập địa chỉ siêu cấp vũ trụ này:
👉 **[http://localhost:8000](http://localhost:8000)**

*Mẹo: Nếu hệ thống bạn đang xung đột Port 8000, hãy vào tệp `docker-compose.yml`, ở Service `webserver`, sửa lại thành `8888:80` chẳng hạn.*
*(Mẹo cho DB: Bạn có thể cắm phần mềm như DBeaver vào host `localhost` port `3307` dùng user `root` / pass `rootpassword` để ngắm bảng).*

---

## 🤝 Hướng dẫn tham gia Bơm code (Contributing Guidelines)

Chúng tôi cực kỳ hoan nghênh những dòng code mới từ cộng đồng để dự án ngầu hơn!
1. **Fork** repo này về nhà.
2. Tạo nhanh một bản thể (nhánh) mới: `git checkout -b feature/tinh-nang-ban-muon-lam`.
3. Phép thuật thêm vào và **Commit**: `git commit -m 'Thêm chức năng xịn xò này nè'`.
4. Đẩy nó lên Cloud: `git push origin feature/tinh-nang-ban-muon-lam`.
5. Tạo một **Pull Request** bự chà bá và chờ đợi Review 😎.

---

## 📝 Giấy phép (License) & Tuyên bố trách nhiệm
- Mã nguồn là hoàn toàn ở dạng [MIT License](https://choosealicense.com/licenses/mit/). Bạn có thể lấy xài, sửa thoải mái.
- Dự án là Demo phục vụ giáo dục, chia sẻ kiến thức cộng đồng. Mong nhận được sự tôn trọng và góp ý văn minh.

---
*"Cuộc sống quá ngắn để cố gắng tự xây dựng mọi thứ từ con số Zero mà không dùng lại những bánh xe hình tròn đã được phát minh. Hãy ghép chúng lại với nhau."*  
**❤️ Cảm ơn bạn và Happy Coding!**
