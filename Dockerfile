# Use PHP 8.2 with built-in web server
FROM php:8.2-cli

# Enable PostgreSQL support
RUN docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install

# Expose internal port
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
