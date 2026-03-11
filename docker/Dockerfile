FROM php:8.4-fpm

# 1. Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libcurl4-openssl-dev

# 2. Xóa cache để giảm dung lượng image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Cài đặt các PHP extensions
# pdo_mysql: kết nối DB
# mbstring: xử lý chuỗi
# curl: để gọi API Google/Facebook
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath curl

# 4. Cài đặt Composer mới nhất
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Thiết lập thư mục làm việc
WORKDIR /var/www/html

# 6. Mở cổng 9000 (Mặc định của PHP-FPM)
EXPOSE 9000