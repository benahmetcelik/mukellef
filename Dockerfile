FROM php:8.3.1-fpm

# Set working directory
WORKDIR /var/www

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql pdo_pgsql pgsql zip exif pcntl gd memcached mongodb

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx

# Install supervisor
RUN apt-get install -y supervisor

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy code to /var/www
COPY --chown=www:www-data . /var/www
RUN chmod -R 777 /var/www/storage/
RUN chmod -R 777 /var/www/bootstrap/cache/
# add root to www group
RUN chmod -R ug+w /var/www/storage

# Copy nginx/php/supervisor configs
RUN cp docker/supervisor.conf /etc/supervisord.conf
RUN cp docker/nginx/default.conf /etc/nginx/sites-enabled/default


# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
# .env.example dosyasını .env olarak kopyala
RUN cp -rf .env.example .env

# composer.lock dosyasını sil
RUN rm -rf composer.lock

# Composer bağımlılıklarını yükle
RUN composer install --optimize-autoloader --no-scripts

# Gerekli log dosyalarını oluştur ve izinlerini ayarla
RUN mkdir -p /var/www/storage/logs/worker && \
    touch /var/www/storage/logs/worker/supervisor-events.log && \
    touch /var/www/storage/logs/worker/supervisor-schedule.log && \
    chmod 777 /var/www/storage/logs/worker/supervisor-events.log && \
    chmod 777 /var/www/storage/logs/worker/supervisor-schedule.log

# run.sh betiğine çalıştırma izni ver
RUN chmod +x /var/www/docker/run.sh


ENTRYPOINT ["sh","/var/www/docker/run.sh"]
