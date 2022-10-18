FROM php:7.3-apache
COPY src/ /var/www/html

# Codeigniter4 has index.php in /public so change the Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# The GAE file system is not writeable so this is not needed.  However it
# might be handy for developing an alternative cache so it has been left in.
RUN chown -R www-data:www-data /var/www/html/writable

# Enable the Apache URL rewriter so the Codeigniter routes work.
RUN a2enmod rewrite

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
#ARG GIT_PERSONAL_ACCESS_TOKEN
#RUN composer config -g github-oauth.github.Composer $GIT_PERSONAL_ACCESS_TOKEN
RUN composer install --prefer-dist
#This is lost be because the volume is lost.
# CMD bash -c "composer install --prefer-dist"
COPY docker-entrypoint.sh /usr/local/bin/dockerInit
RUN chmod +x /usr/local/bin/dockerInit
RUN dockerInit
