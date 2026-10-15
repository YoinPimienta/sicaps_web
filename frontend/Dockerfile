FROM php:8.2-cli-alpine

WORKDIR /var/www

# Instalar extensiones necesarias de PHP y herramientas del sistema
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    linux-headers \
    bash \
    && docker-php-ext-install pdo pdo_pgsql opcache pcntl bcmath

# Configurar OPcache para máximo rendimiento
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'opcache.save_comments=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todo el código fuente del frontend al contenedor
COPY . /var/www

# Asignar permisos ejecutables a start.sh
RUN chmod +x /var/www/start.sh

# Exponer el puerto por defecto
EXPOSE 8000

# Ejecutar script de inicio
CMD ["/bin/bash", "/var/www/start.sh"]
