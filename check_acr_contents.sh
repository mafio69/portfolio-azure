#!/bin/bash

# Ustaw zmienne środowiskowe
export ACR_NAME=mariuszregistry
export ACR_LOGIN_SERVER=mariuszregistry.azurecr.io
export RESOURCE_GROUP=rg-portfolio-php-app

# Zaloguj się do rejestru kontenerów
az acr login --name $ACR_NAME

# Listuj repozytoria w rejestrze
echo "Repozytoria w rejestrze $ACR_LOGIN_SERVER:"
az acr repository list --name $ACR_NAME --output table

# Dla każdego repozytorium, listuj tagi
for repo in $(az acr repository list --name $ACR_NAME -o tsv); do
  echo "Tagi dla repozytorium $repo:"
  az acr repository show-tags --name $ACR_NAME --repository $repo --output table
done

# Pokaż szczegóły ostatniego obrazu w każdym repozytorium
for repo in $(az acr repository list --name $ACR_NAME -o tsv); do
  echo "Szczegóły ostatniego obrazu w repozytorium $repo:"
  az acr repository show --name $ACR_NAME --repository $repo --output table
done
