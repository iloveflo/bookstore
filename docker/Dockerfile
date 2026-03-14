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

# --- PHẦN THÊM MỚI ĐỂ BẮT LOG ---
# Trỏ file log vật lý của Nginx thẳng ra màn hình console (stdout/stderr)
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log
# --------------------------------

# 8. Cấu hình Supervisor thay cho start.sh
# Đã bổ sung các cờ stdout_logfile và stderr_logfile để ép Supervisor nhả log ra ngoài
RUN echo '[supervisord]\n\nodaemon=true\n\user=root\n\logfile=/dev/null\n\logfile_maxbytes=0\n\[program:php-fpm]\n\command=php-fpm\n\autostart=true\n\autorestart=true\n\stdout_logfile=/dev/stdout\n\stdout_logfile_maxbytes=0\n\stderr_logfile=/dev/stderr\n\stderr_logfile_maxbytes=0\n\[program:nginx]\n\command=nginx -g "daemon off;"\n\autostart=true\n\autorestart=true\n\stdout_logfile=/dev/stdout\n\stdout_logfile_maxbytes=0\n\stderr_logfile=/dev/stderr\n\stderr_logfile_maxbytes=0' > /etc/supervisor/conf.d/supervisord.conf

# 9. Mở cổng 80
EXPOSE 80

# 10. Lệnh khởi chạy cuối cùng dùng Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]