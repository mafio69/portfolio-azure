#!/usr/bin/env bash
set -euo pipefail
cp /home/site/wwwroot/nginx.conf /etc/nginx/nginx.conf
if command -v php-fpm8.3 >/dev/null 2>&1; then
  php-fpm8.3 -D
elif command -v php-fpm >/dev/null 2>&1; then
  php-fpm -D
else
  echo "Brak php-fpm 8.3 w środowisku"; exit 1
fi

chmod +x deploy_package/startup.sh

exec nginx -g 'daemon off;'c
