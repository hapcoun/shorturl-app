FROM nginx:alpine

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY . /var/www

WORKDIR /var/www

EXPOSE 80