FROM debian:11

ARG APP_ENV
ARG APACHE_CONF=000-default.conf

ENV APP_ENV=${APP_ENV}

RUN apt-get update && apt-get install -y \
    lsb-release apt-transport-https ca-certificates wget gnupg2 curl unzip git

RUN wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.4 \
    composer \
    libapache2-mod-php8.4 \
    php8.4-mysql \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-zip \
    php8.4-intl \
    php8.4-gd \
    php8.4-dom \
    && apt-get clean

RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html
WORKDIR /var/www/html

# Installation conditionnelle des dépendances avec ou sans les packages de dev
RUN if [ "$APP_ENV" = "dev" ]; then \
      composer install --no-interaction; \
    else \
      composer install --no-interaction --optimize-autoloader --no-dev; \
    fi

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
