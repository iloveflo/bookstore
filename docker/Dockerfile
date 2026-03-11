FROM php:8.4-fpm

# 1. Cài đặt các thư viện hệ thống và Nginx
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Cài đặt các PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath curl

# 3. Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Thiết lập thư mục làm việc
WORKDIR /var/www/html

# 5. COPY TOÀN BỘ SOURCE CODE vào container
# (Render cần source code nằm sẵn trong image, không dùng volumes như local)
COPY . .

# 6. Cài đặt thư viện của project
RUN composer install --no-dev --optimize-autoloader

# 7. Phân quyền cho Laravel (Tránh lỗi Permission Denied khi ghi log/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Copy file cấu hình Nginx của bạn vào đúng chỗ
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# 9. Tạo script để chạy cả Nginx và PHP-FPM cùng lúc
RUN echo '#!/bin/sh\nphp-fpm -D\nnginx -g "daemon off;"' > /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# 10. Mở cổng 80 để Render có thể giao tiếp với Nginx
EXPOSE 80

# 11. Lệnh khởi chạy cuối cùng
CMD ["/usr/local/bin/start.sh"]