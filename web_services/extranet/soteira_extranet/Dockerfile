FROM php:8.1-apache

# Instalamos extensiones necesarias para que db.php funcione (mysqli)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitamos el módulo de reescritura de Apache para URLs amigables
RUN a2enmod rewrite

# Copiamos el código fuente al contenedor
COPY . /var/www/html/

# Ajustamos permisos para que el servidor web pueda leer/escribir
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
