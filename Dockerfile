FROM wordpress:6.2.2-php8.0-apache

# Installing & configuring xDebug
RUN pecl install xdebug-3.1.6 \
  && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini 
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini

# Installing WP CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN php wp-cli.phar --info
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

USER www-data

COPY ./flamingo-gdpr/ /var/www/html/wp-content/plugins/flamingo-gdpr/

CMD [ "apache2-foreground" ]


# wp core install --url=localhost:61247 --title=Example --admin_user=danideso --admin_password=123456Ab! --admin_email=dani@deso.com --skip-email