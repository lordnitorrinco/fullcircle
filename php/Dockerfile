FROM php:8.4-fpm-alpine

# Install necessary extensions
RUN docker-php-ext-install pdo pdo_mysql

# Allow super user - set this if you use Composer as a super user at all times like in docker containers
ENV COMPOSER_ALLOW_SUPERUSER=1

# Obtain composer using multi-stage build
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files and install dependencies
COPY composer.* ./
RUN composer install

# Copy application files to the working directory
COPY . .

# Run composer dump-autoload --optimize
RUN composer dump-autoload --optimize

# Create the fullcircle script
RUN printf '#!/bin/sh\nphp /app/bin/main.php "$@"\n' > /usr/local/bin/fullcircle

# Make the script executable
RUN chmod +x /usr/local/bin/fullcircle