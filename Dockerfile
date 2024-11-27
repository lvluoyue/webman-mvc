FROM php:8.3-cli

# 安装php扩展
CMD pecl install redis
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo_mysql

# 安装Composer，用于开发环境
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 设置工作目录
WORKDIR /opt

# 运行webman
CMD ["php", "start.php", "start"]