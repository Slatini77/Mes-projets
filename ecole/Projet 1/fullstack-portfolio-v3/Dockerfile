FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN { \
  echo "file_uploads = On"; \
  echo "upload_max_filesize = 20M"; \
  echo "post_max_size = 25M"; \
  echo "memory_limit = 256M"; \
} > /usr/local/etc/php/conf.d/uploads.ini

RUN echo 'AddDefaultCharset UTF-8' > /etc/apache2/conf-available/charset.conf \
    && a2enconf charset \
    && echo 'AddDefaultCharset UTF-8' >> /etc/apache2/apache2.conf


ENV LANG=C.UTF-8
ENV LC_ALL=C.UTF-8
ENV TZ=Europe/Paris

RUN a2enmod rewrite

COPY ./src /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
