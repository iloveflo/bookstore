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
    nginx supervisor \
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

# 7. Copy file cấu hình Nginx của bạn vào đúng chỗ
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# 8. Cấu hình Supervisor thay cho start.sh
# Tạo cấu hình báo cho Supervisor chạy cả PHP và Nginx
RUN echo '[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=php-fpm\nautostart=true\nautorestart=true\n\n[program:nginx]\ncommand=nginx -g "daemon off;"\nautostart=true\nautorestart=true' > /etc/supervisor/conf.d/supervisord.conf

# 9. Mở cổng 80
EXPOSE 80

# 10. Lệnh khởi chạy cuối cùng dùng Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]