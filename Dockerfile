FROM php:8.1.9-fpm

LABEL maintainer="Ricardo Coelho <ricardo@nexy.com.br>"

RUN apt-get update \
    && apt-get install -y libssl-dev git zip wget \
    && cd /tmp \
    && wget https://github.com/swoole/swoole-src/archive/refs/heads/master.zip -O swoole-src.zip \
    && unzip swoole-src.zip && cd swoole-src-master \
    && phpize && ./configure && make && make install \
    && cd / && rm -rf /tmp/pear \
    && docker-php-ext-install sockets \
    && docker-php-ext-enable swoole sockets
