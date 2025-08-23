#!/bin/bash

# Nginx Diagnostic Script for Ubuntu WSL
# This script checks common nginx issues in WSL environment

set -e

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

print_header() {
    echo
    echo "================================================"
    echo -e "${BLUE}$1${NC}"
    echo "================================================"
}

echo "🔍 Nginx Diagnostic Tool for Ubuntu WSL"
print_header "System Information"

# Check WSL version
if grep -q Microsoft /proc/version; then
    print_status "Running in WSL environment"
    WSL_VERSION=$(cat /proc/version | grep -o 'WSL[0-9]' || echo "WSL1")
    print_info "WSL Version: $WSL_VERSION"
else
    print_warning "Not running in WSL environment"
fi

# Check Ubuntu version
if [ -f /etc/os-release ]; then
    . /etc/os-release
    print_info "OS: $NAME $VERSION"
fi

print_header "Nginx Installation Check"

# Check if nginx is installed
if command -v nginx &> /dev/null; then
    NGINX_VERSION=$(nginx -v 2>&1 | grep -o 'nginx/[0-9.]*')
    print_status "Nginx is installed: $NGINX_VERSION"
else
    print_error "Nginx is not installed"
    echo "Run: sudo apt update && sudo apt install nginx"
    exit 1
fi

# Check nginx configuration syntax
print_info "Testing nginx configuration..."
if sudo nginx -t 2>/dev/null; then
    print_status "Nginx configuration syntax is valid"
else
    print_error "Nginx configuration syntax error"
    echo "Error details:"
    sudo nginx -t
fi

print_header "Service Status Check"

# Check nginx service status
if systemctl is-active --quiet nginx; then
    print_status "Nginx service is running"
else
    print_error "Nginx service is not running"
    echo "Status:"
    sudo systemctl status nginx --no-pager -l
fi

# Check if nginx is enabled
if systemctl is-enabled --quiet nginx; then
    print_status "Nginx service is enabled (will start on boot)"
else
    print_warning "Nginx service is not enabled"
    echo "Run: sudo systemctl enable nginx"
fi

# Check PHP-FPM status
if command -v php-fpm8.3 &> /dev/null || command -v php8.3-fpm &> /dev/null; then
    if systemctl is-active --quiet php8.3-fpm; then
        print_status "PHP-FPM 8.3 is running"
    else
        print_warning "PHP-FPM 8.3 is installed but not running"
        echo "Run: sudo systemctl start php8.3-fpm"
    fi
else
    print_warning "PHP-FPM 8.3 is not installed (needed for backend)"
    echo "Run: sudo apt install php8.3-fpm"
fi

print_header "Port and Network Check"

# Check if port 80 is available/in use
PORT_80_CHECK=$(netstat -tlnp 2>/dev/null | grep ":80 " || echo "")
if [ -n "$PORT_80_CHECK" ]; then
    print_status "Port 80 is in use:"
    echo "$PORT_80_CHECK"
else
    print_error "Port 80 is not in use - nginx might not be listening"
fi

# Check if port 8000 is available (for backend)
PORT_8000_CHECK=$(netstat -tlnp 2>/dev/null | grep ":8000 " || echo "")
if [ -n "$PORT_8000_CHECK" ]; then
    print_status "Port 8000 is in use (backend server):"
    echo "$PORT_8000_CHECK"
else
    print_warning "Port 8000 is not in use - backend server might not be running"
fi

# Check if port 5173 is available (for Vite dev server)
PORT_5173_CHECK=$(netstat -tlnp 2>/dev/null | grep ":5173 " || echo "")
if [ -n "$PORT_5173_CHECK" ]; then
    print_status "Port 5173 is in use (Vite dev server):"
    echo "$PORT_5173_CHECK"
else
    print_warning "Port 5173 is not in use - Vite dev server might not be running"
fi

print_header "Configuration Files Check"

# Check if our nginx site configuration exists
if [ -f "/etc/nginx/sites-available/portfolio" ]; then
    print_status "Portfolio nginx config exists in sites-available"
else
    print_error "Portfolio nginx config not found in sites-available"
    echo "Run: sudo cp nginx.conf /etc/nginx/sites-available/portfolio"
fi

# Check if our nginx site is enabled
if [ -L "/etc/nginx/sites-enabled/portfolio" ]; then
    print_status "Portfolio nginx config is enabled"
else
    print_error "Portfolio nginx config is not enabled"
    echo "Run: sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/"
fi

# Check if default site is still enabled
if [ -f "/etc/nginx/sites-enabled/default" ]; then
    print_warning "Default nginx site is still enabled (might conflict)"
    echo "Consider removing: sudo rm /etc/nginx/sites-enabled/default"
fi

print_header "File Permissions and Paths"

# Check if project directory exists and is readable
PROJECT_DIR="/home/mariusz/projects/portfolio_azure"
if [ -d "$PROJECT_DIR" ]; then
    print_status "Project directory exists: $PROJECT_DIR"
    
    # Check if frontend dist directory exists
    if [ -d "$PROJECT_DIR/frontend/dist" ]; then
        print_status "Frontend dist directory exists"
    else
        print_warning "Frontend dist directory not found"
        echo "Run: cd frontend && npm run build"
    fi
    
    # Check if backend public directory exists
    if [ -d "$PROJECT_DIR/backend/public" ]; then
        print_status "Backend public directory exists"
    else
        print_warning "Backend public directory not found"
    fi
    
else
    print_error "Project directory not found: $PROJECT_DIR"
fi

# Check PHP-FPM socket
if [ -S "/var/run/php/php8.3-fpm.sock" ]; then
    print_status "PHP-FPM socket exists"
else
    print_warning "PHP-FPM socket not found"
    echo "Check: sudo find /var/run -name '*php*sock*'"
fi

print_header "Log Files Check"

# Check nginx error log
if [ -f "/var/log/nginx/portfolio_error.log" ]; then
    print_info "Portfolio error log exists"
    ERROR_COUNT=$(wc -l < /var/log/nginx/portfolio_error.log 2>/dev/null || echo "0")
    if [ "$ERROR_COUNT" -gt 0 ]; then
        print_warning "Found $ERROR_COUNT lines in error log"
        echo "Recent errors:"
        sudo tail -5 /var/log/nginx/portfolio_error.log 2>/dev/null || echo "Cannot read error log"
    fi
else
    print_info "No portfolio-specific error log found yet"
fi

# Check main nginx error log
if [ -f "/var/log/nginx/error.log" ]; then
    print_info "Main nginx error log exists"
    echo "Recent nginx errors:"
    sudo tail -5 /var/log/nginx/error.log 2>/dev/null || echo "Cannot read main error log"
fi

print_header "WSL-Specific Checks"

# Check if systemd is working properly in WSL
if command -v systemctl &> /dev/null; then
    if systemctl --version &>/dev/null; then
        print_status "Systemd is working properly"
    else
        print_error "Systemd is not working properly"
        print_info "This is common in older WSL versions"
    fi
else
    print_error "Systemctl not found"
fi

# Check if we can access localhost
if curl -s --connect-timeout 5 http://localhost &>/dev/null; then
    print_status "Can connect to localhost"
else
    print_warning "Cannot connect to localhost"
fi

print_header "Recommendations"

echo "Based on the diagnostic results, here are the recommendations:"
echo
echo "1. If nginx is not running:"
echo "   sudo systemctl start nginx"
echo
echo "2. If configuration is not enabled:"
echo "   sudo cp nginx.conf /etc/nginx/sites-available/portfolio"
echo "   sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/"
echo
echo "3. If backend is not running:"
echo "   ./start-backend.sh"
echo
echo "4. If frontend is not built:"
echo "   cd frontend && npm run build"
echo
echo "5. For development mode, start both servers and update nginx config:"
echo "   ./start-backend.sh"
echo "   cd frontend && npm run dev"
echo "   # Then edit /etc/nginx/sites-available/portfolio to enable Vite proxy"
echo
echo "6. Test the setup:"
echo "   curl -I http://localhost"
echo "   curl -I http://localhost/api"
echo
echo "7. View logs if issues persist:"
echo "   sudo tail -f /var/log/nginx/portfolio_error.log"

echo
print_status "Diagnostic completed! Check the recommendations above."