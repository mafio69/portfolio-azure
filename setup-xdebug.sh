#!/bin/bash

# Xdebug Setup Script for Portfolio Application
# This script installs and configures Xdebug for PHP debugging with PhpStorm

set -e  # Exit on any error

echo "🔧 Setting up Xdebug for PHP debugging..."
echo "========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root. Please run as a regular user."
   exit 1
fi

# Check if Xdebug configuration file exists
if [[ ! -f "backend/config/xdebug.ini" ]]; then
    print_error "Xdebug configuration file not found. Please run this script from the project root directory."
    exit 1
fi

print_info "Installing Xdebug for PHP 8.3..."

# Update package list
sudo apt update

# Install Xdebug
if ! php -m | grep -q xdebug; then
    print_info "Installing php8.3-xdebug package..."
    sudo apt install -y php8.3-xdebug
    print_status "Xdebug installed successfully"
else
    print_status "Xdebug is already installed"
fi

# Copy configuration file to CLI directory
print_info "Configuring Xdebug for CLI..."
sudo cp backend/config/xdebug.ini /etc/php/8.3/cli/conf.d/20-xdebug.ini
print_status "CLI configuration updated"

# Copy configuration file to FPM directory if it exists
if [ -d "/etc/php/8.3/fpm/conf.d" ]; then
    print_info "Configuring Xdebug for PHP-FPM..."
    sudo cp backend/config/xdebug.ini /etc/php/8.3/fpm/conf.d/20-xdebug.ini
    print_status "FPM configuration updated"
    
    # Restart PHP-FPM if it's running
    if systemctl is-active --quiet php8.3-fpm; then
        print_info "Restarting PHP-FPM..."
        sudo systemctl restart php8.3-fpm
        print_status "PHP-FPM restarted"
    fi
else
    print_info "PHP-FPM not installed, skipping FPM configuration"
fi

# Verify Xdebug installation
print_info "Verifying Xdebug installation..."
if php -m | grep -q xdebug; then
    print_status "Xdebug is loaded and ready"
    
    # Show Xdebug version
    XDEBUG_VERSION=$(php -m | grep xdebug)
    print_info "Xdebug module: $XDEBUG_VERSION"
    
    # Show configuration
    print_info "Current Xdebug configuration:"
    php -i | grep -E "(xdebug\.mode|xdebug\.client_host|xdebug\.client_port|xdebug\.idekey)" || print_warning "Could not retrieve Xdebug configuration"
else
    print_error "Xdebug installation verification failed"
    exit 1
fi

echo
echo "========================================="
echo -e "${GREEN}🎉 Xdebug setup completed successfully!${NC}"
echo
echo "Configuration details:"
echo "- Mode: debug"
echo "- Client Host: host.docker.internal"
echo "- Client Port: 9003"
echo "- IDE Key: PHPSTORM"
echo "- Start with request: yes"
echo "- Discover client host: disabled"
echo
echo "Next steps:"
echo "1. Configure PhpStorm debugging (see PHPSTORM_KONFIGURACJA.md)"
echo "2. Set PhpStorm debug port to 9003"
echo "3. Set breakpoints in your PHP code"
echo "4. Start debugging session in PhpStorm"
echo "5. Make a request to your PHP application"
echo
echo "To test Xdebug:"
echo "  php -r \"var_dump(extension_loaded('xdebug'));\""
echo
echo "To view full Xdebug info:"
echo "  php -r \"phpinfo();\" | grep -i xdebug"
echo "========================================="