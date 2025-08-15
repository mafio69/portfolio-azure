#!/bin/bash
echo "Uruchamianie backendu PHP..."
cd backend
echo "Sprawdzanie czy composer install został wykonany..."
if [ ! -d "vendor" ]; then
    echo "Instalowanie zależności Composer..."
    composer install
fi
echo "Uruchamianie serwera PHP na localhost:8000..."
echo "Backend będzie dostępny pod: http://localhost:8000"
echo "Aby zatrzymać serwer, naciśnij Ctrl+C"
php -S localhost:8000 -t public