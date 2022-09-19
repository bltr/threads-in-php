FROM php:7.4-zts-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME='/composer'

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN set -ex \
#------------------------------------------------------------------
#  extensions
#------------------------------------------------------------------
    && install-php-extensions \
        zip \
        parallel \
#------------------------------------------------------------------
    && php -v \
    && php -m \
#
#------------------------------------------------------------------
#  Composer
#------------------------------------------------------------------
    && mkdir $COMPOSER_HOME \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php  --ansi --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && composer --ansi --version \
    && chmod -R 0777 $COMPOSER_HOME

WORKDIR /app