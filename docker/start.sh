#!/bin/sh
php-fpm &
nginx -c /etc/nginx/nginx.conf -g 'daemon off;'
