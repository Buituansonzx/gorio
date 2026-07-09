FROM nginx:alpine

# Copy config nginx
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy thư mục public của Laravel
COPY ../../public /var/www/html/public
