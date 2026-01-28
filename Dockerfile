# --- ETAPA 1: Compilar Assets (JS/CSS) ---
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- ETAPA 2: Configurar PHP/Laravel ---
FROM php:8.4-apache

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql bcmath gd mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar y habilitar extensión de REDIS
RUN pecl install redis && docker-php-ext-enable redis

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Configurar el directorio raíz de Apache a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar el código del proyecto
WORKDIR /var/www/html
COPY . .

# Copiar los assets compilados desde la etapa anterior
COPY --from=assets /app/public/build ./public/build

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# PERMISOS TOTALES: Esto elimina el error de "Permission denied" en los logs
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 80

# Comando final optimizado:
# 1. Espera a que la red se estabilice.
# 2. Limpia la configuración para leer las nuevas variables (la IP).
# 3. Corre las migraciones automáticamente.
CMD sleep 5 && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan migrate --force --no-interaction ; \
    apache2-foreground