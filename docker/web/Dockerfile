FROM debian:buster

WORKDIR /var/www

RUN apt update -y && apt upgrade -y \
    && apt install php7.3 -y \
    && apt install php-mysqli -y \
    && apt install composer -y \ 
    && apt install php-xml -y \
    && apt install cron -y \
    && apt install php-xdebug -y
