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

# Copiar los assets compilados desde la etapa anterior (Vite/Tailwind)
COPY --from=assets /app/public/build ./public/build

# Instalar Composer de forma global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalación sin scripts para evitar errores de red en el build
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Ajustar permisos para carpetas de escritura de Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto 80
EXPOSE 80

# Comando final optimizado para Plan Gratuito:
# 1. Espera 5 segundos para estabilidad de red.
# 2. Limpia caché para leer las variables de Render.
# 3. Ejecuta migraciones automáticamente.
CMD sleep 5 && php artisan config:clear && php artisan migrate --force --no-interaction ; apache2-foreground