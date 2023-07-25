# Dockerfile
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y --no-install-recommends zlib1g-dev g++ git libicu-dev libssl-dev zip libzip-dev zip make\
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install sockets \
    && git clone --depth 1 --branch v2.2.0 https://github.com/edenhill/librdkafka.git \
    && cd librdkafka \
    && ./configure && make && make install \
    && cd ../ && rm -rf librdkafka \
    && pecl install rdkafka \
    && echo "extension=rdkafka.so" > /usr/local/etc/php/conf.d/rdkafka.ini

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get update -y && apt-get install -y libmcrypt-dev symfony-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Source code
WORKDIR /var/www

RUN export LATEST_VERSION=$(curl -s https://api.github.com/repos/infection/infection/releases/latest |grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/') \
    && echo "Latest version is:" $LATEST_VERSION \
    && curl -L -so /var/www/infection.phar https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar \
    && curl -L -so /var/www/infection.phar.asc https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar.asc \
    && cd /var/www \
    && chmod +x infection.phar \
    && gpg --recv-keys C6D76C329EBADE2FB9C458CFC5095986493B4AA0 \
    && gpg --with-fingerprint --verify infection.phar.asc infection.phar \
    && mv infection.phar /usr/local/bin/infection

#EXPOSE 8000
#CMD symfony server:ca:install
#CMD symfony server:start
