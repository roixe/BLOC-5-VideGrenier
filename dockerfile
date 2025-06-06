FROM debian:11

ARG APP_ENV
ARG APACHE_CONF=000-default.conf

RUN apt-get update && apt-get install -y \
    lsb-release apt-transport-https ca-certificates wget gnupg2 curl unzip git

RUN wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.4 \
    composer \
    composer install --no-interaction --optimize-autoloader\
    libapache2-mod-php8.4 \
    php8.4-mysql \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-zip \
    php8.4-intl \
    php8.4-gd \
    php8.4-dom \
    $(if [ "$APP_ENV" = "dev" ]; then echo "php-pear"; fi) \
    && apt-get clean \
    && if [ "$APP_ENV" = "dev" ]; then \
        composer require --dev phpunit/phpunit ^9.5; \
    fi

RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache/${APACHE_CONF} /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
