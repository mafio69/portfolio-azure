#!/bin/bash
cp /home/site/wwwroot/nginx.conf /etc/nginx/nginx.conf

# Uruchom nginx w trybie foreground (nie jako daemon)
exec nginx -g 'daemon off;'
