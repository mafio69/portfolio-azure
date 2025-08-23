# Konfiguracja PhpStorm dla projektu Portfolio

## Sprawdzenie aktualnej konfiguracji
Twój PhpStorm jest już w dużej mierze skonfigurowany poprawnie:

✅ **Interpreter PHP 8.3** - skonfigurowany z WSL Ubuntu-24.04
✅ **Composer autoloading** - vendor foldery są poprawnie wykluczane  
✅ **Namespace mapping** - `App\` -> `backend/src/`
✅ **Narzędzia deweloperskie** - PHPStan, CodeSniffer skonfigurowane

## Co może wymagać poprawy

### 1. Konfiguracja Xdebug (debugging)

#### Instalacja Xdebug w WSL:
```bash
# W terminalu WSL Ubuntu
sudo apt update
sudo apt install php8.3-xdebug

# Sprawdź czy jest zainstalowane
php -m | grep xdebug
```

#### Konfiguracja Xdebug w PHP:
Dodaj do `/etc/php/8.3/cli/conf.d/20-xdebug.ini`:
```ini
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
```

#### W PhpStorm:
1. **File → Settings → PHP → Debug**
   - Port: 9003
   - Zaznacz "Can accept external connections"
   
2. **File → Settings → PHP → Servers**
   - Dodaj nowy serwer:
   - Name: `localhost`
   - Host: `localhost`
   - Port: `8080`
   - Debugger: `Xdebug`

### 2. Konfiguracja Node.js i Vue.js

#### Ustawienie interpretera Node.js:
1. **File → Settings → Languages & Frameworks → Node.js and NPM**
   - Node interpreter: wskaż na Node.js w WSL
   - Package manager: `npm` lub `yarn`

#### Włączenie Vue.js support:
1. **File → Settings → Plugins**
   - Zainstaluj plugin "Vue.js" (jeśli nie jest zainstalowany)
   
2. **File → Settings → Languages & Frameworks → JavaScript → Libraries**
   - Dodaj `Vue.js` library

#### TypeScript configuration:
1. **File → Settings → Languages & Frameworks → TypeScript**
   - TypeScript service: Włączone
   - Wskaż na `frontend/tsconfig.json`

### 3. Konfiguracja struktury projektu

#### Oznaczanie folderów:
1. Kliknij prawym na `backend/src` → **Mark Directory as → Sources Root**
2. Kliknij prawym na `backend/tests` → **Mark Directory as → Test Sources Root**
3. Kliknij prawym na `frontend/src` → **Mark Directory as → Sources Root**
4. Kliknij prawym na `frontend/tests` → **Mark Directory as → Test Sources Root**

### 4. Uruchamianie i debugowanie

#### Konfiguracja uruchamiania PHP:
1. **Run → Edit Configurations → + → PHP Built-in Web Server**
   - Name: `Backend Server`
   - Host: `localhost`
   - Port: `8080`
   - Document root: `backend/public`
   - Use a router script: `backend/public/index.php`

#### Konfiguracja uruchamiania Vue.js:
1. **Run → Edit Configurations → + → npm**
   - Name: `Frontend Dev Server`
   - Package.json: `frontend/package.json`
   - Command: `run`
   - Scripts: `dev`

### 5. Przydatne ustawienia

#### Code Style:
1. **File → Settings → Editor → Code Style → PHP**
   - Wybierz PSR-12 standard

#### Live Templates:
1. **File → Settings → Editor → Live Templates**
   - Włącz templates dla PHP, Vue.js, TypeScript

#### Git integration:
1. **File → Settings → Version Control → Git**
   - Sprawdź czy ścieżka do git jest poprawna

## Testowanie konfiguracji

### Test PHP debugging:
1. Ustaw breakpoint w `backend/src/Action/ListProjectsAction.php` (linia 22)
2. Uruchom backend server w trybie debug
3. Wywołaj `curl http://localhost:8080/api/projects`
4. PhpStorm powinien zatrzymać się na breakpoincie

### Test Vue.js development:
1. Uruchom `npm run dev` w folderze `frontend`
2. Otwórz `http://localhost:3000`
3. Hot reload powinien działać przy zmianach w kodzie

## Rozwiązywanie problemów

### Problem: "PHP interpreter not configured"
- Sprawdź **File → Settings → PHP → CLI Interpreter**
- Dodaj interpreter WSL: `\\wsl$\Ubuntu-24.04\usr\bin\php`

### Problem: "Composer autoload not found"
- Sprawdź **File → Settings → PHP → Composer**
- Path to composer.json: `backend/composer.json`

### Problem: "TypeScript errors"
- Sprawdź **File → Settings → Languages & Frameworks → TypeScript**
- Upewnij się że TypeScript service jest włączony

### Problem: Błędy z polskimi znakami
- **File → Settings → Editor → File Encodings**
- Ustaw wszystkie encoding na UTF-8
- IDE Encoding: UTF-8
- Project Encoding: UTF-8
- Properties Files: UTF-8

## Skróty klawiaturowe (przydatne)

- `Ctrl + Shift + F10` - Uruchom aktualny plik
- `F5` - Debuguj step into
- `F6` - Debuguj step over  
- `F7` - Debuguj step out
- `Ctrl + F8` - Toggle breakpoint
- `Ctrl + Shift + F8` - View breakpoints

## Dodatkowe pluginy (opcjonalne)

Zainstaluj przez **File → Settings → Plugins**:
- `.env files support`
- `PHP Annotations`
- `Symfony Support`
- `Tailwind CSS`
- `GitToolBox`

---

Po zastosowaniu tych ustawień Twój PhpStorm powinien działać płynnie z projektem PHP/Slim + Vue.js/TypeScript!