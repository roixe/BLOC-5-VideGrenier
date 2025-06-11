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
    php8.1 \
    libapache2-mod-php8.1 \
    php8.1-mysql \
    php8.1-xml \
    php8.1-mbstring \
    php8.1-curl \
    php8.1-zip \
    php8.1-intl \
    php8.1-gd \
    php8.1-dom \
    unzip \
    curl \
    && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY apache/${APACHE_CONF} /etc/apache2/sites-available/000-default.conf

# Copie du projet
COPY . /var/www/html
WORKDIR /var/www/html

# Installation conditionnelle
RUN if [ "$APP_ENV" = "prod" ]; then \
    composer install --no-dev --optimize-autoloader; \
  else \
    composer install; \
  fi

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]
