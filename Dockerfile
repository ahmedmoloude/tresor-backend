FROM php:8.3.0-apache

# Rename php.ini-production to php.ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Adjust memory_limit to unlimited (-1)
RUN echo "memory_limit = -1" >> /usr/local/etc/php/php.ini

# Adjust upload_max_filesize and post_max_size
RUN echo "upload_max_filesize = 200M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 200M" >> /usr/local/etc/php/php.ini

    
RUN echo "extension=pdo_pgsql\n" \
    "extension=pgsql\n" \
    >> /usr/local/etc/php/php.ini

RUN a2enmod rewrite headers deflate 

# install necessary extentions
RUN apt-get update -y && apt-get install -y libpng-dev git libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install gd
RUN docker-php-ext-install sockets


# install pgsql
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# install redis
# RUN pecl install -o -f redis \
#     &&  rm -rf /tmp/pear \
#     &&  docker-php-ext-enable redis

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# setup a work directory
WORKDIR /var/www/html


COPY composer.json ./

RUN composer install --no-scripts --no-autoloader

COPY . ./

RUN composer dump-autoload --optimize  --no-scripts

RUN rm /etc/apache2/sites-available/000-default.conf && rm /etc/apache2/sites-enabled/000-default.conf
ADD vhost.docker.conf /etc/apache2/sites-available/vhost.docker.conf
RUN a2ensite vhost.docker.conf

RUN chmod -R 777 .


RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache
RUN chmod -R 775 storage/framework
RUN chown -R www-data:www-data storage/framework
RUN chown -R www-data:www-data /var/www/html

USER www-data


EXPOSE 80

