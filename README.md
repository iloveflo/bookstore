<div align="center">
  <h1>📚 BookWorm</h1>
  <p>Một ứng dụng quản lý cửa hàng sách chuyên nghiệp và mạnh mẽ, được xây dựng trên nền tảng PHP theo kiến trúc MVC tự định nghĩa (Custom MVC Architecture). Dự án kết hợp khéo léo giữa các thư viện phổ biến như Eloquent ORM, Bramus Router, Plates Templating cùng với môi trường Container hóa Docker để mang lại trải nghiệm phát triển và triển khai dễ dàng nhất.</p>
  <strong>🌍 Live Demo: <a href="https://bookworm-b69w.onrender.com" target="_blank">https://bookworm-b69w.onrender.com</a></strong><br>
  <strong>⚠️ Lưu ý: Dự án chỉ mang tính chất học tập, không phải sản phẩm thương mại.</strong>
</div>

---

## 📖 Bối cảnh & Mục tiêu dự án (Background & Purpose)

**BookWorm** được khởi tạo với mục tiêu cung cấp một ví dụ thực tế về cách xây dựng một Web Application hoàn chỉnh bằng PHP thuần (Vanilla PHP) nhưng áp dụng các tiêu chuẩn thiết kế hiện đại (Modern Design Patterns). Thay vì sử dụng một framework khổng lồ như Laravel hay Symfony, dự án này lắp ráp các "mảnh ghép" (components) rời rạc lại với nhau thông qua Composer.

Điều này giúp sinh viên, lập trình viên mới làm quen với PHP hiểu rõ hơn về:
- Cách một Request được tiếp nhận và xử lý (Request Lifecycle).
- Cơ chế Autoloading chuẩn PSR-4 của Composer hoạt động ra sao.
- Cách triển khai hệ thống ORM (Object-Relational Mapping) độc lập.
- Tính tổ chức, tách biệt logic (Controller), giao diện (View) và dữ liệu (Model) trong kiến trúc MVC.

---

## 🚀 Các tính năng nổi bật (Key Features)

Dự án cung cấp một loạt các tính năng cơ bản nhưng thiết yếu cho một hệ thống quản lý, thương mại điện tử quy mô nhỏ:

1. **🛍️ Quản lý sản phẩm (Sách) & Phân loại (Danh mục)**
   - Hiển thị danh sách các đầu sách với giao diện trực quan.
   - Phân loại sách theo từng danh mục cụ thể (Ví dụ: Văn học, Khoa học, IT...).
   - Chức năng tìm kiếm, lọc và phân trang (Pagination).
   - Xem chi tiết thông tin sách (Tác giả, Nhà xuất bản, Giá, Mô tả).

2. **🔐 Hệ thống Xác thực & Phân quyền nâng cao (Authentication & Authorization)**
   - Đăng nhập (Login) và Đăng ký (Register) người dùng thông thường.
   - **Tích hợp OAuth2**: Hỗ trợ đăng nhập nhanh bằng nền tảng thứ 3 như **Google** và **Facebook** (được thiết lập sẵn cấu hình qua file `.env`).
   - Quản lý phiên đăng nhập an toàn thông qua lớp `SessionGuard`.
   - Tính năng "Quên mật khẩu" (Forgot Password): Sinh token reset mật khẩu an toàn và gửi liên kết khôi phục tới người dùng bằng Email thật thông qua `PHPMailer` (hỗ trợ SMTP).

3. **🏗️ Kiến trúc & Mã nguồn chất lượng cao**
   - **Custom MVC Approach**: Tổ chức mã nguồn sạch sẽ, dễ mở rộng.
   - **Routing chuyên nghiệp**: Sử dụng `Bramus Router` để định tuyến các URL thân thiện (SEF - Search Engine Friendly) với hỗ trợ Middleware và Prefix.
   - **Templating linh hoạt**: Tích hợp `League/Plates` cho phép tạo Layout master, kế thừa View (View Inheritance) giống như Blade của Laravel.
   - **Cơ sở dữ liệu mạnh mẽ**: Không còn phải viết những câu truy vấn SQL thuần thô sơ. Dự án tích hợp `Illuminate Database` (Eloquent của Laravel), cho phép thiết lập Relationship (1-n, n-n) dễ dàng, an toàn chống lại SQL Injection.

4. **🐳 Triển khai nhàn tênh với Docker (Containerization)**
   - Cấu hình sẵn sàng `docker-compose.yml`.
   - Môi trường gồm 3 Services: 
     - 🖥️ **Nginx (Webserver)**: Nhận và điều phối request (port 8000).
     - 🐘 **PHP-FPM (App)**: Xử lý logic ứng dụng.
     - 🐬 **MySQL 8.0 (Database)**: Lưu trữ dữ liệu.
   - Tự động import Database mẫu cực tiện lợi ngay trong lần khởi chạy đầu tiên (`bookstore.sql`).

---

## 🛠 Điểm tâm Công nghệ (Technology Stack)

Ứng dụng được nuôi dưỡng bởi các công nghệ dưới đây:

### Backend 
- **Ngôn ngữ:** [PHP 8.x+](https://www.php.net/) - Ngôn ngữ lập trình chính.
- **Dependency Manager:** [Composer](https://getcomposer.org/) - Quản lý thư viện.
- **Routing:** [bramus/router](https://github.com/bramus/router) (^1.6) - Xử lý điều hướng linh hoạt, nhanh nhẹn.
- **Templating Engine:** [league/plates](https://platesphp.com/) (3.*) - Native PHP Templates, không cần compile như Twig hay Blade.
- **ORM / Database:** [illuminate/database](https://github.com/illuminate/database) (^11.0) - Trái tim quản lý dữ liệu xuất sắc.
- **Environment:** [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) (^5.4) - Quản lý bảo mật các thông tin nhạy cảm qua `.env`.
- **Mailing:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) (^7.0) - Cỗ máy gửi email đáng tin cậy.

### Infrastructure & DevOps
- **Docker** & **Docker Compose**
- **Nginx** (Alpine version)
- **MySQL** (Tag: 8.0)

### 🚀 CI/CD Pipeline & Cloud Deployment
- **GitHub Actions:** Tự động hóa luồng CI/CD mỗi khi có thay đổi trên nhánh `main`.
- **Docker Hub:** Lưu trữ và quản lý Docker Images (Container Registry).
- **Render:** Triển khai (hosting services) ứng dụng Web App tự động dựa trên Docker Image.
- **Aiven:** Cloud Database hosting được sử dụng để chạy MySQL trên môi trường production.

---

## 📁 Cấu trúc thư mục chi tiết (Deep Dive into Folder Structure)

Để dễ dàng nắm bắt, dưới đây là giải thích luồng hoạt động thông qua cấu trúc thư mục:

```text
BookWorm/
├── app/                  # (BỘ NÃO) Khu vực chứa mã PHP chính yếu
│   ├── Controllers/      # Chứa các Class điều khiển luồng (VD: AuthController, HomeController...)
│   │   └── Auth/         # Nhóm Controller xử lý logic Đăng nhập/Đăng ký/Quên mật khẩu
│   ├── Models/           # Các Class Eloquent, đại diện cho các bảng trong DB
│   ├── SessionGuard.php  # Lớp Custom bảo vệ, quản lý Authentication State
│   └── functions.php     # Global Helper functions (các hàm hỗ trợ dùng ở mọi nơi)
├── config/               # Cấu hình phụ (nếu thiết lập mở rộng)
├── docker/               
│   ├── Dockerfile        # Các bước setup Image cho PHP container
│   └── nginx/
│       └── default.conf  # Cấu hình Nginx thiết lập DocumentRoot trỏ vào /public
├── public/               # (CỬA VÀO - ENTRY POINT) Chỉ thư mục này public ra web
│   ├── css/, js/         # Chứa CSS, JavaScript, Images tĩnh
│   └── index.php         # Tệp DUY NHẤT nhận Request -> Nạp Bootstrap -> Route
├── vendor/               # (THƯ VIỆN) Chứa toàn bộ package của Composer (không đẩy lên Git)
├── views/                # (GIAO DIỆN) Nơi chứa các tệp HTML kết hợp PHP (Plates logic)
│   ├── layouts/          # Chứa bố cục chung (Header, Footer, Sidebar)
│   └── ...               # Các file template theo từng chức năng (auth, home, error...)
├── .env                  # Lưu trữ biến môi trường (Credentials, Mail Config...)
├── bootstrap.php         # Tệp cực quan trọng khởi động Core: load autoload, thiết lập DB Manager
├── composer.json         # Khai báo dependency và rule chạy chuẩn PSR-4 Autoload cho thư mục 'app'
├── docker-compose.yml    # Trái tim của toàn bộ lệnh Docker Orchestration
└── bookstore.sql         # Snapshot Database ban đầu, Docker sẽ auto-import file này
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
