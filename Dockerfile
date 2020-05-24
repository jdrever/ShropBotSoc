FROM php:7.3-apache
COPY src/ /var/www/html

# Codeigniter4 has index.php in /public so change the Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# GAE is not writeable so this is not needed.  However it has been left in
# as a reminder that it can be done but should not be.
# RUN chown -R www-data:www-data /var/www/html/writable

# Install XDebug
RUN pecl install -f xdebug \
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
ARG GIT_PERSONAL_ACCESS_TOKEN 
RUN composer config -g github-oauth.github.Composer $GIT_PERSONAL_ACCESS_TOKEN
RUN composer install --prefer-dist
