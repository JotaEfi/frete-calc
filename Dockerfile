FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

# Instalar extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Configurar Apache para Railway
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar composer.json primeiro (para cache das dependências)
COPY composer.json ./
COPY src/composer.json ./src/

# Instalar dependências globais e do src
RUN composer install --no-dev --optimize-autoloader --no-scripts
WORKDIR /var/www/html/src
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Voltar ao diretório raiz
WORKDIR /var/www/html

# Copiar código da aplicação
COPY src/ ./

# Copiar arquivo de produção como .env
COPY .env.production ./.env

# Definir permissões
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Configurar Apache para usar variável PORT do Railway
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf
RUN echo '<VirtualHost *:${PORT}>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Expor a porta do Railway
EXPOSE ${PORT}

# Comando para iniciar Apache com variáveis de ambiente
CMD ["sh", "-c", "apache2-foreground"]