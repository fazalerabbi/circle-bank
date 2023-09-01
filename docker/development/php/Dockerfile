FROM php:8.1-fpm-alpine3.16

# Install dev dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    curl-dev \
    imagemagick-dev \
    libtool \
    libxml2-dev \
    postgresql-dev

# Install production dependencies
RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    g++ \
    gcc \
    git \
    icu-dev \
    icu-libs \
    imagemagick \
    libc-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    make \
    nodejs \
    npm \
    yarn \
    openssh-client \
    postgresql-libs \
    rsync \
    zlib-dev \
    gnu-libiconv

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so
RUN apk add --update linux-headers
# Install PECL and PEAR extensions
RUN pecl install \
    imagick \
    xdebug

# Enable PECL and PEAR extensions
RUN docker-php-ext-enable \
    imagick \
    xdebug

# Configure php extensions
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-configure zip

# Install php extensions
RUN docker-php-ext-install \
    bcmath \
    calendar \
    curl \
    exif \
    gd \
    intl \
    pdo \
    pdo_pgsql \
    pcntl \
    xml \
    zip

# Cleanup dev dependencies
RUN apk del -f .build-deps

# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
# Setup working directory
WORKDIR /var/www/html
# Register aliases
RUN echo 'alias composer="/usr/local/bin/composer"' >> ~/.bashrc
RUN echo 'alias arti="php artisan"' >> ~/.bashrc
RUN composer global require "squizlabs/php_codesniffer=*"

# Setup working directory
WORKDIR /var/www/html
