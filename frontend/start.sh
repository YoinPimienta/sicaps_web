#!/bin/bash
set -e

cd /var/www

# 1. Configurar archivo .env si no existe
if [ ! -f ".env" ]; then
    echo "Creando .env a partir de .env.example..."
    cp .env.example .env
fi

# 2. Generar APP_KEY si no está configurada
if ! grep -q "APP_KEY=base64" .env; then
    echo "Generando clave de aplicación Laravel..."
    php artisan key:generate --force
fi

# 3. Asegurar que las dependencias estén instaladas
if [ ! -f "vendor/autoload.php" ]; then
    echo "Instalando dependencias de composer..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 4. Asegurar directorios de almacenamiento y permisos
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# 5. Optimización de Laravel (caché de rutas, vistas y configuración)
if [ "$APP_ENV" = "production" ] || [ -n "$RENDER" ] || [ -n "$RAILWAY_STATIC_URL" ]; then
    echo "Modo producción detectado: Optimizando cachés de Laravel..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# 6. Determinar el puerto (Render y Railway asignan $PORT dinámicamente)
PORT_TO_USE="${PORT:-8000}"

echo "========================================================="
echo "   Iniciando SICAPS Frontend en puerto: $PORT_TO_USE"
echo "========================================================="

# PHP_CLI_SERVER_WORKERS permite concurrencia de múltiples peticiones en paralelo
export PHP_CLI_SERVER_WORKERS=${PHP_CLI_SERVER_WORKERS:-4}

exec php -d opcache.enable_cli=1 artisan serve --host=0.0.0.0 --port="$PORT_TO_USE" --no-reload
