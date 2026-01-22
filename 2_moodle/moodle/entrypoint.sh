#!/bin/bash
set -e

# Configuración por defecto si no viene de AWS
if [ -z "$MOODLE_URL" ]; then
    export MOODLE_URL="http://localhost"
    echo "ADVERTENCIA: MOODLE_URL no definida, usando localhost"
fi

echo "Iniciando Moodle con URL objetivo: $MOODLE_URL"

# Lógica de instalación automática
if [ ! -f "/var/www/html/config.php" ]; then
    echo "Instalando Moodle..."
    
    # 1. Instalación estándar
    php admin/cli/install.php \
        --lang=es \
        --wwwroot="$MOODLE_URL" \
        --dataroot=/var/www/moodledata \
        --dbtype=mariadb \
        --dbhost="$MOODLE_DB_HOST" \
        --dbname="$MOODLE_DB_NAME" \
        --dbuser="$MOODLE_DB_USER" \
        --dbpass="$MOODLE_DB_PASSWORD" \
        --fullname="Moodle AWS" \
        --shortname="MoodleAWS" \
        --adminuser=admin \
        --adminpass=Admin123! \
        --adminemail=admin@example.com \
        --non-interactive \
        --agree-license

    echo "Aplicando parche de configuración dinámica para AWS..."

    # 2. ELIMINAR la última línea del config.php (require_once)
    sed -i '$d' /var/www/html/config.php

    # 3. AÑADIR el bloque dinámico
    cat <<EOF >> /var/www/html/config.php

// --- AWS ELASTIC BEANSTALK CONFIGURATION START ---
if (getenv('MOODLE_URL')) {
    \$CFG->wwwroot = getenv('MOODLE_URL');
}

if (isset(\$_SERVER['HTTP_X_FORWARDED_PROTO']) && \$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    \$CFG->wwwroot = str_replace('http://', 'https://', \$CFG->wwwroot);
    \$CFG->sslproxy = 1;
}
// --- AWS ELASTIC BEANSTALK CONFIGURATION END ---

require_once(__DIR__ . '/lib/setup.php');
EOF

    echo "Configuración parcheada. Ajustando permisos..."
fi

# --- ESTA PARTE ES LA SOLUCIÓN AL ERROR ---
# Aseguramos SIEMPRE (incluso si el archivo ya existía) que los permisos sean correctos
# chown: Cambia el dueño a www-data (Apache)
# chmod 644: El dueño puede escribir, el resto (Apache) puede leer.

if [ -f "/var/www/html/config.php" ]; then
    chown www-data:www-data /var/www/html/config.php
    chmod 644 /var/www/html/config.php
fi

# Aseguramos permisos en todo el directorio html y moodledata por seguridad
chown -R www-data:www-data /var/www/html
chown -R www-data:www-data /var/www/moodledata

echo "Permisos aplicados. Iniciando Apache..."
exec "$@"
