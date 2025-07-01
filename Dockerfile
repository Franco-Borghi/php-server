# Use official PHP CLI image (v8.2)
FROM php:8.2-cli

# Install PostgreSQL development libraries and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Set the working directory inside the container
WORKDIR /app

# Copy the entire project into the container
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install

# Expose the internal port
EXPOSE 8080

# Start the built-in PHP development server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
