#!/bin/bash

# Ten skrypt zatrzymuje istniejące kontenery i uruchamia je ponownie,
# wymuszając przebudowanie obrazu Docker.

# Zapewnia, że skrypt przerwie działanie, jeśli którekolwiek polecenie zwróci błąd.
set -e

# Przechodzi do katalogu, w którym znajduje się ten skrypt.
# Dzięki temu można go uruchomić z dowolnego miejsca w systemie.
cd "$(dirname "$0")"

echo "Zatrzymywanie istniejących kontenerów (jeśli istnieją)..."
docker-compose down

echo ""
echo "Budowanie i uruchamianie kontenerów w tle..."
docker-compose up -d --build

echo ""
echo "Gotowe! Użyj 'docker ps', aby zobaczyć status kontenerów."
echo "Użyj 'docker-compose logs -f', aby śledzić logi."
