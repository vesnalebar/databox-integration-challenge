# Use the official PHP image as the base image
FROM php:8.3-cli

# Set the working directory
WORKDIR /app

# Install necessary extensions and tools
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
RUN composer install

# Copy the rest of the application code
COPY . .

# Expose port 80
EXPOSE 80

# Default command
CMD ["php", "src/index.php"]
