FROM php:8.4-cli-bookworm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
        intl \
        pdo_pgsql \
        zip \
        opcache \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Installation de Symfony CLI
COPY --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

# Répertoire de travail
WORKDIR /app

# Port du serveur Symfony
EXPOSE 8000

# Démarrage du serveur de développement
CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--allow-all-ip"]