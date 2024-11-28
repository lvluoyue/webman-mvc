FROM php:8.3-cli

RUN apt-get update && apt-get install -y libpq-dev libzip-dev libbz2-dev \
    libwebp-dev libjpeg-dev libpng-dev libxpm-dev libfreetype6-dev libvpx-dev

# 安装php扩展
RUN docker-php-ext-install pcntl pdo_mysql pdo_pgsql sockets zip bz2 gd
RUN pecl install redis

# 安装Composer，用于开发环境
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 设置挂载点
VOLUME ["/opt"]

# 设置工作目录
WORKDIR /opt

# 运行webman
ENTRYPOINT ["php", "start.php", "start"]