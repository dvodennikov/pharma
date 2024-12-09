FROM debian

WORKDIR /var/www/html

RUN apt-get update;\apt-get upgrade;\apt-get --yes install apache2 php php-mysql php-pgsql

RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

COPY . .

RUN chown -R www-data:www-data /var/www/html;\chmod u+rw /var/www/html

COPY ./docker/apache2.conf /etc/apache2/apache2.conf

COPY ./docker/000-default.conf /etc/apache2/sites-enabled/000-default.conf

CMD systemctl start apache2

CMD sleep 3600

EXPOSE 80
