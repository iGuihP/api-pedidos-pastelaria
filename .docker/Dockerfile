FROM alpine:3.18.3 as default
WORKDIR /var/www/html

RUN apk update && apk upgrade \
    && apk add bash \
    && apk add nginx \
    && apk add php81 \
    && apk add curl libxml2-dev \
    && apk add php81-fpm php81-opcache php81-pdo php81-mysqli php81-pdo_mysql \
    && apk add php81-gd php81-zlib php81-curl php81-iconv php81-phar \
    && apk add gd \
    && apk add php81-fileinfo \
    && apk add php81-session

RUN apk add composer

COPY .docker/nginx-default.conf /etc/nginx/nginx.conf
COPY .docker/nginx-server.conf /etc/nginx/conf.d/server.conf
COPY .docker/entrypoint.sh /entrypoint.sh

COPY .docker/php-fpm-default.conf /etc/php81/php-fpm.conf
COPY .docker/php-fpm-server.conf /etc/php81/php-fpm.d/php-fpm-server.conf

RUN mkdir /var/run/php

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm81", "-F"]

FROM default AS dev
ENV ENV=development
ENV PHP_IDE_CONFIG="serverName=host.docker.internal"
RUN apk add php81-pecl-xdebug