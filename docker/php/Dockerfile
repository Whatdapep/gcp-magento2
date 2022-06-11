FROM php:7.4-fpm-alpine

# Copy composer.lock and composer.json
# COPY ./app/composer.lock ./app/composer.json /var/www/magento/

# Set working directory
WORKDIR /var/www/magento/

# Install dependencies
# RUN apt-get update && apt-get install -y \
#     build-essential \
#     libpng-dev \
#     libjpeg62-turbo-dev \
#     libfreetype6-dev \
#     locales \
#     zip \
#     jpegoptim optipng pngquant gifsicle \
#     vim \
#     unzip \
#     git \
#     curl \
#     libzip-dev

# Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN apk update && apk upgrade

RUN apk -u add bash git

# Install extensions
# RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl          
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
# RUN docker-php-ext-install gd

# RUN docker-php-ext-install bcmath
# RUN docker-php-ext-install intl
# RUN docker-php-ext-install soap
# RUN docker-php-ext-install json
# RUN docker-php-ext-install curl
# RUN docker-php-ext-install cli
# RUN docker-php-ext-install xml
# RUN docker-php-ext-install xmlrpc
# RUN docker-php-ext-install gmp
# RUN docker-php-ext-install common

# Install composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN curl -sS https://getcomposer.org/installer -o composer-setup.php | php composer-setup.php --install-dir=/usr/bin --filename=composer


ADD docker/php/install-php.sh /usr/sbin/install-php.sh
RUN chmod +x /usr/sbin/install-php.sh
RUN /usr/sbin/install-php.sh

# Copy existing application directory contents
COPY *.ini /usr/local/etc/php/conf.d/

# Add user for laravel application
RUN groupadd -g 1000 www-data
RUN useradd -u 1000 -ms /bin/bash -g www-data www-data

RUN mkdir /var/www/magento



# Copy existing application directory contents
# COPY ./app /var/www/magento

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/magento

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
