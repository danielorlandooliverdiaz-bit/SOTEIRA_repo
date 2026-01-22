FROM php:8.2-apache

# 1. Instalar dependencias del sistema necesarias para las extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libxslt1-dev \
    unzip \
    ghostscript \
    locales \
    && rm -rf /var/lib/apt/lists/*

# 2. Configurar e instalar extensiones de PHP requeridas por Moodle
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    intl \
    soap \
    mysqli \
    zip \
    opcache \
    exif \
    xsl \
    mbstring

# 3. Aumentar límites de PHP (Moodle necesita memoria y tiempos de ejecución altos)
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/moodle-limits.ini \
    && echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/moodle-limits.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/moodle-limits.ini \
    && echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/moodle-limits.ini \
    && echo "max_input_vars = 5000" >> /usr/local/etc/php/conf.d/moodle-limits.ini

# 4. Descargar Moodle (Usamos la rama estable MOODLE_403_STABLE como ejemplo)
# Puedes cambiar la rama o versión según necesites.
WORKDIR /var/www/html
RUN git clone -b MOODLE_403_STABLE --depth 1 https://github.com/moodle/moodle.git .

# 5. Crear el directorio moodledata (fuera del root web por seguridad)
RUN mkdir -p /var/www/moodledata \
    && chown -R www-data:www-data /var/www/moodledata \
    && chmod -R 777 /var/www/moodledata \
    && chown -R www-data:www-data /var/www/html

# 6. Habilitar mod_rewrite de Apache
RUN a2enmod rewrite





# Copiar el entrypoint
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
