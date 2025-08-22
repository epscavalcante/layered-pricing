FROM php:8.4-fpm-alpine

ARG user=application
ARG uid=1000

WORKDIR /var/www

# Instala dependências de build e PCOV
RUN apk add --no-cache $PHPIZE_DEPS bash zip curl \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && echo "pcov.enabled=1" > /usr/local/etc/php/conf.d/00-pcov.ini \
    && echo "pcov.directory=/var/www" >> /usr/local/etc/php/conf.d/00-pcov.ini \
    && echo "pcov.exclude=vendor" >> /usr/local/etc/php/conf.d/00-pcov.ini \
    && apk del $PHPIZE_DEPS

# Instala extensão PHP necessária
RUN docker-php-ext-install pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário
RUN addgroup -S $user \
    && adduser -S -u $uid -G $user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Copiar código da aplicação
COPY . /var/www
RUN chown -R $user:$user /var/www

USER $user
