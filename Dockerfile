FROM php:7.4-apache
COPY ./src/ /var/www/html/

# Codeigniter4 has index.php in /public so change the Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Provide a writeable area for the file cache.
# RUN chown -R www-data:www-data /var/www/html/public/writable

# Enable the Apache URL rewriter so the Codeigniter routes work.
RUN a2enmod rewrite

# Install XDebug (need older version due to PHP 7.4 dependence)
RUN pecl install -f xdebug-3.1.5 \
  && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

# Install Codeigniter Dependencies
RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev g++ libzip-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# For some strange reason the ARG needs to be defined, go figure.
#ARG GIT_PERSONAL_ACCESS_TOKEN
#RUN composer config -g github-oauth.github.Composer $GIT_PERSONAL_ACCESS_TOKEN
RUN composer install --prefer-dist
#This is lost be because the volume is lost.
# CMD bash -c "composer install --prefer-dist"
COPY docker-entrypoint.sh /usr/local/bin/dockerInit
RUN chmod +x /usr/local/bin/dockerInit
RUN dockerInit
