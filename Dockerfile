FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer de forma mais robusta
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

# Instalar extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar composer.json primeiro (para cache das dependências)
COPY composer.json ./

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar código da aplicação
COPY src/ ./

# Definir permissões
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expor porta 80
EXPOSE 80