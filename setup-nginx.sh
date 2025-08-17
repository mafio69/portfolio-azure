#!/bin/bash

# Nginx Setup Script for Portfolio Application
# This script automates the nginx configuration process

set -e  # Exit on any error

echo "🚀 Setting up Nginx for Portfolio Application..."
echo "================================================"

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

# Get the current directory (project root)
PROJECT_ROOT=$(pwd)
print_info "Project root: $PROJECT_ROOT"

# Check if we're in the right directory
if [[ ! -f "nginx.conf" ]]; then
    print_error "nginx.conf not found. Please run this script from the project root directory."
    exit 1
fi

# Update package list
print_info "Updating package list..."
sudo apt update

# Install nginx if not already installed
if ! command -v nginx &> /dev/null; then
    print_info "Installing nginx..."
    sudo apt install -y nginx
    print_status "Nginx installed successfully"
else
    print_status "Nginx is already installed"
fi

# Install PHP-FPM if not already installed
if ! systemctl is-active --quiet php8.3-fpm; then
    print_info "Installing PHP-FPM..."
    sudo apt install -y php8.3-fpm php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath
    print_status "PHP-FPM installed successfully"
else
    print_status "PHP-FPM is already installed and running"
fi

# Copy nginx configuration
print_info "Copying nginx configuration..."
sudo cp nginx.conf /etc/nginx/sites-available/portfolio

# Create symbolic link
print_info "Creating symbolic link..."
sudo ln -sf /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/

# Remove default nginx site (optional, ask user)
if [[ -f "/etc/nginx/sites-enabled/default" ]]; then
    read -p "Do you want to remove the default nginx site? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        sudo rm /etc/nginx/sites-enabled/default
        print_status "Default nginx site removed"
    fi
fi

# Test nginx configuration
print_info "Testing nginx configuration..."
if sudo nginx -t; then
    print_status "Nginx configuration is valid"
else
    print_error "Nginx configuration test failed. Please check the configuration."
    exit 1
fi

# Start and enable services
print_info "Starting and enabling services..."
sudo systemctl start nginx
sudo systemctl enable nginx
sudo systemctl start php8.3-fpm
sudo systemctl enable php8.3-fpm

# Reload nginx
sudo systemctl reload nginx

print_status "Services started and enabled"

# Check service status
print_info "Checking service status..."
if systemctl is-active --quiet nginx; then
    print_status "Nginx is running"
else
    print_error "Nginx failed to start"
    exit 1
fi

if systemctl is-active --quiet php8.3-fpm; then
    print_status "PHP-FPM is running"
else
    print_warning "PHP-FPM is not running (this is okay for development mode)"
fi

# Check if port 80 is available
if netstat -tlnp 2>/dev/null | grep -q ":80 "; then
    print_status "Port 80 is in use (nginx is listening)"
else
    print_warning "Port 80 doesn't seem to be in use by nginx"
fi

echo
echo "================================================"
echo -e "${GREEN}🎉 Nginx setup completed successfully!${NC}"
echo
echo "Next steps:"
echo "1. Start your development servers:"
echo "   - Backend: ./start-backend.sh"
echo "   - Frontend: cd frontend && npm run dev"
echo
echo "2. For development mode, edit /etc/nginx/sites-available/portfolio"
echo "   and uncomment the Vite proxy configuration (see nginx-setup.md)"
echo
echo "3. Access your application:"
echo "   - Frontend: http://localhost"
echo "   - API: http://localhost/api"
echo
echo "4. View logs:"
echo "   - Error log: sudo tail -f /var/log/nginx/portfolio_error.log"
echo "   - Access log: sudo tail -f /var/log/nginx/portfolio_access.log"
echo
echo "For detailed instructions, see nginx-setup.md"
echo "================================================"