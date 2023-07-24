# Dockerfile
FROM php:8.1-fpm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get update -y && apt-get install -y libmcrypt-dev symfony-cli

#RUN export LATEST_VERSION=$(curl -s https://api.github.com/repos/infection/infection/releases/latest |grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/') \
#    && echo "Latest version is:" $LATEST_VERSION \
#    && curl -L -so /usr/local/bin/infection.phar https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar \
#    && curl -L -so /usr/local/bin/infection.phar.asc https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar.asc \
#    && cd /usr/local/bin \
#    && chmod +x infection.phar \
#    && gpg --recv-keys C6D76C329EBADE2FB9C458CFC5095986493B4AA0 \
#    && gpg --with-fingerprint --verify infection.phar.asc infection.phar

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN docker-php-ext-install pdo mbstring

RUN mkdir /opt/currency_api_project
WORKDIR /opt/currency_api_project
COPY . .

RUN composer install

RUN export LATEST_VERSION=$(curl -s https://api.github.com/repos/infection/infection/releases/latest |grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/') \
    && echo "Latest version is:" $LATEST_VERSION \
    && curl -L -so /opt/currency_api_project/infection.phar https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar \
    && curl -L -so /opt/currency_api_project/infection.phar.asc https://github.com/infection/infection/releases/download/${LATEST_VERSION}/infection.phar.asc \
    && cd /opt/currency_api_project \
    && chmod +x infection.phar \
    && gpg --recv-keys C6D76C329EBADE2FB9C458CFC5095986493B4AA0 \
    && gpg --with-fingerprint --verify infection.phar.asc infection.phar \
    && mv infection.phar /usr/local/bin/infection

EXPOSE 8000
CMD symfony server:ca:install
CMD symfony server:start
