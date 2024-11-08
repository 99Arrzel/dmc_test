
ARG uid=1000  # Default UID if not provided during build
FROM php:8.3-apache
RUN apt-get update
# 1. development packages
RUN apt-get install -y \
  git \
  zip \
  curl \
  sudo \
  unzip \
  libonig-dev \
  libzip-dev \
  libicu-dev \
  libbz2-dev \
  libxslt-dev \
  libpng-dev \
  libjpeg-dev \
  libmcrypt-dev \
  libreadline-dev \
  libfreetype6-dev \
  g++
# # 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/dmc-test/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# # 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers
# 4. start with base php config, then add extensions
# RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN pecl install mcrypt-1.0.7 && docker-php-ext-enable mcrypt
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug 
RUN docker-php-ext-install xsl
RUN docker-php-ext-install \
  bz2 \
  intl \
  bcmath \
  iconv \
  bcmath \
  opcache \
  calendar \
  mbstring \
  pdo_mysql \
  mysqli \
  zip 
# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
# RUN cd /var/www/html && composer install --no-interaction --no-plugins --no-scripts
# 6. we need a user with the same UID/GID with host user
# so when we execute CLI commands, all the host file's ownership remains intact
# otherwise command from inside container will create root-owned files and directories
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \ 
  chown -R devuser:devuser /home/devuser

EXPOSE 80
