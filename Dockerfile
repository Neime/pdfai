FROM php:8.2-fpm AS app_php

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip imagick

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update -y && apt install -y ghostscript
RUN sed -i '/disable ghostscript format types/,+6d' /etc/ImageMagick-6/policy.xml