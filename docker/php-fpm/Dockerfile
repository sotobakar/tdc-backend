FROM php:8.1-fpm-alpine

# Install necessary packages
RUN apk add --no-cache \
  nodejs npm \
  libpng-dev \
  libzip-dev \
  jpeg-dev \
  zip \
  unzip \
  icu-dev \
  oniguruma-dev \
  libxml2-dev \
  openssl-dev

# Configure GD Extensions
RUN docker-php-ext-configure gd --enable-gd --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
  pdo_mysql \
  gd \
  zip \
  intl \
  mbstring \
  bcmath \
  opcache \
  exif \
  pcntl \
  soap \
  sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy necessary files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --prefer-dist --no-interaction --no-scripts --no-autoloader \
  && composer clear-cache \
  && rm -rf /tmp/*

COPY . .

# Generate autoload files
RUN composer dump-autoload --classmap-authoritative

# Set the appropriate file permissions
RUN chown -R www-data:www-data /var/www/storage && \
  chown -R www-data:www-data /var/www/bootstrap/cache && \
  chmod -R 775 /var/www/storage && \
  chmod -R 775 /var/www/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
