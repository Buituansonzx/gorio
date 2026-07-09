FROM ubuntu:22.04

# Cập nhật và cài các công cụ cơ bản
RUN apt-get update && \
    apt-get install -y sudo dialog apt-utils software-properties-common curl ca-certificates gnupg2 lsb-release

# Thêm repository PHP từ Ondrej
RUN add-apt-repository ppa:ondrej/php -y && \
    apt-get update

# Cài PHP 8.2 và các extensions cần thiết
RUN apt-get install -y \
    php8.2 php8.2-fpm php8.2-cli php8.2-curl php8.2-fileinfo php8.2-gd php8.2-zip php8.2-pgsql \
    php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath

# Cài các gói khác: cron, nginx, git, unzip, vim, supervisor
RUN apt-get install -y cron nginx git unzip vim zip supervisor mysql-client

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# PHP conf
RUN mkdir /run/php && chmod 777 -R /etc/php/8.2/fpm/
ADD _docker/php-fpm/www.conf /etc/php/8.2/fpm/pool.d/www.conf
ADD _docker/php-fpm/php-fpm.conf /etc/php/8.2/fpm/php-fpm.conf
ADD _docker/php-fpm/php.ini /etc/php/8.2/fpm/php.ini
ADD _docker/php-fpm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD _docker/nginx/nginx.default.conf /etc/nginx/sites-available/default
ADD _docker/php-fpm/crontab /etc/cron.d/cron

RUN chmod u+s /usr/sbin/cron && \
    chmod u+s /usr/sbin/nginx && \
    chmod u+s /usr/sbin/php-fpm8.2

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/html
USER www-data

# Tạo thư mục supervisor log
RUN mkdir -p /tmp/supervisor/cron /tmp/supervisor/nginx /tmp/supervisor/php-fpm \
    /tmp/supervisor/queue /tmp/supervisor/websocket /tmp/supervisor/horizon

EXPOSE 80
EXPOSE 443
EXPOSE 8080

CMD ["supervisord"]
