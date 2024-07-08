FROM php:8.3-apache

RUN apt-get update && apt-get install -y wget gnupg g++ locales unzip dialog apt-utils git && apt-get clean

# install NodeJS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get update && apt-get install -y nodejs && apt-get clean

# install development packages and clean up apt cache.
RUN apt-get update && \
    apt-get install -y \
    bash-completion \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    vim \
    nano \
    mc \
    htop \
    ffmpeg \
    autoconf && \
    apt-get clean

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# apache configs + document root.
RUN echo "ServerName croix.local" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# start with base PHP config, then add extensions.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# install necessary tools for running application
RUN docker-php-ext-install \
    bcmath \
    fileinfo \
    gd \
    intl \
    opcache \
    pgsql \
    pdo_pgsql \
    zip \
    mysqli \
    pdo \
    pdo_mysql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer.
COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

# we need a user with the same UID/GID as the host user
# so when we execute CLI commands, all the host file's permissions and ownership remain intact.
# Otherwise commands from inside the container would create root-owned files and directories.
ARG uid

RUN useradd -G www-data,root -u $uid -d /home/www www \
    # Create the necessary directory for Composer
    && mkdir -p /home/www/.composer \
    # Set ownership of the home directory
    && chown -R www:www /home/www

# Copy the application files and set ownership
COPY --chown=www:www . /var/www

# Switch to the newly created user for the following commands
USER www
