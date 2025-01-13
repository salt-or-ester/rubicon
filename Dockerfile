FROM php:8.0-alpine

# Install required packages
RUN apk add --no-cache --update --virtual .build-deps \
    build-base \
    hiredis-dev \
    zlib-dev \
    wget \
    autoconf \
    git

# Download and extract Redis source
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.7.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1

RUN apk add --no-cache redis
RUN pecl install redis && docker-php-ext-enable redis

RUN apk add --no-cache curl
RUN apk add --no-cache composer

WORKDIR /var/www/html
RUN git clone --single-branch --branch master https://gitgud.io/saltorester/rubicon.git

WORKDIR /var/www/html/rubicon
COPY wordlists /wordlists

WORKDIR /var/www/html/rubicon/wordlists
RUN wget https://raw.githubusercontent.com/ignis-sec/Pwdb-Public/master/wordlists/ignis-100K.txt

RUN docker-php-ext-install pcntl
EXPOSE 6379
COPY redis.conf /usr/local/etc/redis/redis.conf

WORKDIR /var/www/html/rubicon
EXPOSE 8888

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set the entrypoint script as the entrypoint
ENTRYPOINT ["/entrypoint.sh"]