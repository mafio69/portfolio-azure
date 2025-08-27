#!/bin/bash
set -e  # Exit on any error

PROJECT_ROOT=$(pwd)
BACKEND_DIR="$PROJECT_ROOT/backend"

# Kolory dla output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🧪 Running PHP Tests...${NC}"

# Sprawdź czy katalog backend istnieje
if [ ! -d "$BACKEND_DIR" ]; then
    echo -e "${RED}❌ Backend directory not found!${NC}"
    exit 1
fi

# Sprawdź czy PHPUnit istnieje
if [ ! -f "$BACKEND_DIR/vendor/bin/phpunit" ]; then
    echo -e "${RED}❌ PHPUnit not found! Run: composer install${NC}"
    exit 1
fi

# Wejdź do katalogu backend
cd "$BACKEND_DIR"

# Uruchom PHPUnit
echo -e "${YELLOW}📁 Running tests in: $(pwd)${NC}"
./vendor/bin/phpunit "$@"

# Status code check
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed!${NC}"
else
    echo -e "${RED}❌ Some tests failed!${NC}"
    exit 1
fi

# Powrót do katalogu głównego
cd "$PROJECT_ROOT"
