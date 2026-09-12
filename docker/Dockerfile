FROM php:5.6-apache

USER root

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libmcrypt-dev \
        libicu-dev \
        libxml2-dev \
        libcurl4-gnutls-dev \
        zip \
        curl \
	vim \
        unzip \
	cron \
	mariadb-client \
	libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-configure gd --with-freetype --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install calendar mcrypt json xml gettext mbstring curl \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install intl \
    && docker-php-source delete \
#LibXL
    && curl -fsSL 'http://www.libxl.com/download/libxl-lin-3.6.5.tar.gz' -o /tmp/libxl.tar.gz \
	&& tar zxvf /tmp/libxl.tar.gz --directory /tmp/ \
	&& mkdir -p /opt/libxl \
	&& ls -la /tmp/libxl-* \
	&& cp -r /tmp/libxl-3.6.5.0/* /opt/libxl \
	&& ls -la /opt/libxl \
# PHP Excel extension
    && curl -fsSL 'https://github.com/iliaal/php_excel/archive/php5.zip' -o /tmp/php_excel.zip \
	&& unzip -o /tmp/php_excel.zip -d /tmp \
	&& cd /tmp/php_excel-php5 \
	&& phpize \
	&& ./configure  --with-php-config=$(which php-config) --with-libxl-incdir=/opt/libxl/include_c/ --with-libxl-libdir=/opt/libxl/lib64/ --with-excel=/opt/libxl --with-libxml-dir=/usr/include/libxml2 \
	&& make -j$(nproc) \
	&& make install \
	# && cp /tmp/php_excel-php5/modules/excel.so /usr/local/lib/php/extensions/no-debug-non-zts-20151012/excel.so \
	&& docker-php-source delete \
    && rm -r /tmp/*


COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN COMPOSER_MEMORY_LIMIT=-1 composer self-update 2.0.7

COPY docker/example-crontab /etc/cron.d/example-crontab

RUN chmod 0600 /etc/cron.d/example-crontab

RUN crontab /etc/cron.d/example-crontab

RUN touch /var/log/cron.log

RUN service cron start

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite && a2enmod headers


