FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
COPY . /var/www/html/INTEGRACION-CONTINUA
RUN chown -R www-data:www-data /var/www/html/INTEGRACION-CONTINUA/subidas
EXPOSE 80
