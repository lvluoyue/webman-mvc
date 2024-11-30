FROM php:8.3-cli

# 安装Composer，用于开发环境
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer /usr/bin/composer /usr/bin/

# 安装必要依赖
RUN apt-get update && apt-get install -y libssl-dev libcurl4-openssl-dev libpq-dev libzip-dev libbz2-dev \
    libwebp-dev libjpeg-dev libpng-dev libxpm-dev libfreetype6-dev libvpx-dev unzip

# 安装php扩展
RUN docker-php-ext-install pcntl pdo_mysql pdo_pgsql sockets zip bz2 gd
RUN pecl install redis
RUN pecl install -D 'enable-openssl="yes" enable-swoole-curl="yes" enable-http2="yes" enable-swoole-thread="yes"' swoole

#RUN echo "extension=swoole.so" >> /usr/local/etc/php/php.ini

# 设置挂载点
VOLUME ["/opt"]

# 设置工作目录
WORKDIR /opt

# 运行webman
ENTRYPOINT ["php", "-d", "extension=swoole", "start.php", "start"]