# Jak zresetować Nginx - Przewodnik Reset Nginx

Ten przewodnik wyjaśnia jak zresetować konfigurację Nginx dla projektu Portfolio w różnych scenariuszach.

## 🔄 Skrypt Reset Nginx

Projekt zawiera komprehensywny skrypt `reset-nginx.sh` który oferuje różne opcje resetowania Nginx.

### Uruchomienie skryptu

```bash
# Z głównego katalogu projektu
./reset-nginx.sh
```

## 📋 Dostępne opcje resetowania

### 1. Soft Reset (Miękki reset)
- **Co robi**: Zatrzymuje usługi i usuwa tylko konfigurację portfolio
- **Kiedy używać**: Gdy chcesz usunąć konfigurację portfolio ale zachować podstawowy nginx
- **Bezpieczeństwo**: Bezpieczny, tworzy kopię zapasową

### 2. Configuration Reset (Reset konfiguracji)
- **Co robi**: Usuwa konfigurację portfolio i przywraca domyślną konfigurację nginx
- **Kiedy używać**: Gdy konfiguracja portfolio jest uszkodzona i chcesz wrócić do czystego stanu
- **Bezpieczeństwo**: Bezpieczny, tworzy kopię zapasową i restartuje usługi

### 3. Service Reset (Reset usług)
- **Co robi**: Zatrzymuje i ponownie uruchamia wszystkie usługi nginx
- **Kiedy używać**: Gdy nginx nie odpowiada lub jest zawiesiony
- **Bezpieczeństwo**: Bardzo bezpieczny, nie zmienia konfiguracji

### 4. Log Reset (Reset logów)
- **Co robi**: Czyści wszystkie pliki logów nginx
- **Kiedy używać**: Gdy logi są za duże lub chcesz zacząć od czystych logów
- **Bezpieczeństwo**: Bezpieczny, tworzy kopię zapasową logów

### 5. Complete Reset (Kompletny reset) ⚠️
- **Co robi**: Kompletnie usuwa nginx i wszystkie jego konfiguracje
- **Kiedy używać**: Tylko w przypadku poważnych problemów lub gdy chcesz zacząć od zera
- **Bezpieczeństwo**: **DESTRUKCYJNY** - usuwa wszystko związane z nginx

### 6. Show Status (Pokaż status)
- **Co robi**: Wyświetla aktualny status nginx i konfiguracji
- **Kiedy używać**: Do sprawdzenia aktualnego stanu przed resetem
- **Bezpieczeństwo**: Całkowicie bezpieczny, tylko odczytuje informacje

## 🛡️ Funkcje bezpieczeństwa

### Automatyczne kopie zapasowe
Skrypt automatycznie tworzy kopie zapasowe przed każdą operacją:
- Konfiguracja nginx (`/etc/nginx`)
- Pliki logów (`/var/log/nginx`)
- Lokalizacja: `/tmp/nginx_backup_YYYYMMDD_HHMMSS`

### Przywracanie z kopii zapasowej
```bash
# Przywróć konfigurację z kopii zapasowej
sudo cp -r /tmp/nginx_backup_*/nginx /etc/

# Restartuj nginx
sudo systemctl restart nginx
```

## 🚀 Typowe scenariusze użycia

### Scenario 1: Nginx nie startuje
```bash
# 1. Sprawdź status
./reset-nginx.sh
# Wybierz opcję 6 (Show Status)

# 2. Jeśli problem z konfiguracją, użyj Configuration Reset
./reset-nginx.sh
# Wybierz opcję 2 (Configuration Reset)
```

### Scenario 2: Portfolio nie działa, ale nginx tak
```bash
# Użyj Soft Reset
./reset-nginx.sh
# Wybierz opcję 1 (Soft Reset)

# Następnie ponownie skonfiguruj portfolio
./setup-nginx.sh
```

### Scenario 3: Nginx zawiesił się
```bash
# Użyj Service Reset
./reset-nginx.sh
# Wybierz opcję 3 (Service Reset)
```

### Scenario 4: Błędy w logach utrudniają debugowanie
```bash
# Wyczyść logi
./reset-nginx.sh
# Wybierz opcję 4 (Log Reset)
```

### Scenario 5: Wszystko nie działa, chcę zacząć od nowa
```bash
# Complete Reset (OSTROŻNIE!)
./reset-nginx.sh
# Wybierz opcję 5 (Complete Reset)
# Potwierdź wybór pisząc 'y'

# Następnie zainstaluj i skonfiguruj nginx od nowa
./setup-nginx.sh
```

## 🔧 Ręczne komendy resetowania

Jeśli wolisz ręczne podejście:

### Zatrzymaj nginx
```bash
sudo systemctl stop nginx
sudo systemctl stop php8.3-fpm
```

### Usuń konfigurację portfolio
```bash
sudo rm /etc/nginx/sites-enabled/portfolio
sudo rm /etc/nginx/sites-available/portfolio
sudo rm /var/log/nginx/portfolio_*.log
```

### Przywróć domyślną konfigurację
```bash
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
```

### Uruchom nginx
```bash
sudo systemctl start nginx
sudo systemctl start php8.3-fpm
```

## 📝 Po resecie

Po wykonaniu resetu nginx:

1. **Sprawdź status**:
   ```bash
   sudo systemctl status nginx
   curl -I http://localhost
   ```

2. **Skonfiguruj ponownie portfolio** (jeśli potrzebne):
   ```bash
   ./setup-nginx.sh
   ```

3. **Uruchom serwery deweloperskie**:
   ```bash
   # Backend
   ./start-backend.sh
   
   # Frontend
   cd frontend
   npm run dev
   ```

4. **Sprawdź logi** w przypadku problemów:
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

## 🆘 Rozwiązywanie problemów

### Problem: Skrypt nie ma uprawnień
```bash
chmod +x reset-nginx.sh
```

### Problem: Brak sudo
```bash
# Upewnij się że jesteś w grupie sudo
groups $USER
```

### Problem: Nginx nie instaluje się
```bash
sudo apt update
sudo apt install nginx
```

### Problem: Kopia zapasowa nie działa
```bash
# Sprawdź miejsce na dysku
df -h /tmp

# Sprawdź uprawnienia
ls -la /etc/nginx
```

## 📚 Powiązane skrypty

- `setup-nginx.sh` - Instalacja i konfiguracja nginx
- `diagnose-nginx.sh` - Diagnostyka problemów z nginx
- `nginx.conf` - Główny plik konfiguracji portfolio

## ⚠️ Ostrzeżenia

1. **Complete Reset** jest destrukcyjny - usuwa całkowicie nginx
2. Zawsze sprawdź kopie zapasowe przed resetem
3. Po Complete Reset musisz ponownie zainstalować nginx
4. Upewnij się że masz uprawnienia sudo
5. Nie uruchamiaj skryptu jako root

## 📞 Wsparcie

Jeśli reset nie rozwiązuje problemów:

1. Sprawdź logi: `sudo journalctl -u nginx -f`
2. Uruchom diagnostykę: `./diagnose-nginx.sh`
3. Sprawdź dokumentację: `nginx-setup.md`
4. Sprawdź konfigurację: `sudo nginx -t`