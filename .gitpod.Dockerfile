FROM gitpod/workspace-full

RUN sudo apt-get update -q \
    && sudo apt-get install -y php-dev

RUN wget http://xdebug.org/files/xdebug-2.9.1.tgz \
    && tar -xvzf xdebug-2.9.1.tgz \
    && cd xdebug-2.9.1 \
    && phpize \
    && ./configure \
    && make \
    && sudo mkdir -p /usr/lib/php/20190902 \
    && sudo cp modules/xdebug.so /usr/lib/php/20190902 \
    && sudo bash -c "echo -e '\nzend_extension = /usr/lib/php/20190902/xdebug.so\n\n[XDebug]\nxdebug.remote_enable=1\nxdebug.remote_autostart=1\nxdebug.remote_port=9001\nxdebug.remote_connect_back=0\nxdebug.remote_host=host.docker.internal\nxdebug.remote_log=/workspace/captain-magenta/shit.txt' >> /etc/php/7.4/apache2/php.ini"

ENV APACHE_DOCROOT_IN_REPO="src/public"

EXPOSE 9001
