#!/usr/bin/env bash
set -euo pipefail

# Podmień konfigurację Nginx
cp /home/site/wwwroot/nginx.conf /etc/nginx/nginx.conf

# Uruchom php-fpm w tle (nazwa binarki zależna od środowiska)
if command -v php-fpm8.2 >/dev/null 2>&1; then
  php-fpm8.3 -D
elif command -v php-fpm >/dev/null 2>&1; then
  php-fpm -D
else
  echo "Brak php-fpm w środowisku. Użyj planu/obrazu z PHP-FPM lub zmień stack."
  exit 1
fi

# Uruchom Nginx w foreground (dla App Service)
exec nginx -g 'daemon off;'
