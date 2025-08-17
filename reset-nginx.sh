#!/bin/bash

# Nginx Reset Script for Portfolio Application
# This script provides various options to reset nginx configuration and state

set -e  # Exit on any error

echo "🔄 Nginx Reset Tool for Portfolio Application"
echo "============================================="

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

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root. Please run as a regular user."
   exit 1
fi

# Create backup directory with timestamp
BACKUP_DIR="/tmp/nginx_backup_$(date +%Y%m%d_%H%M%S)"

# Function to create backup
create_backup() {
    print_info "Creating backup in: $BACKUP_DIR"
    mkdir -p "$BACKUP_DIR"
    
    # Backup nginx configuration files
    if [ -d "/etc/nginx" ]; then
        sudo cp -r /etc/nginx "$BACKUP_DIR/" 2>/dev/null || print_warning "Could not backup /etc/nginx"
        print_status "Nginx configuration backed up"
    fi
    
    # Backup log files
    if [ -d "/var/log/nginx" ]; then
        sudo cp -r /var/log/nginx "$BACKUP_DIR/" 2>/dev/null || print_warning "Could not backup nginx logs"
        print_status "Nginx logs backed up"
    fi
    
    print_status "Backup created successfully: $BACKUP_DIR"
}

# Function to stop nginx services
stop_services() {
    print_header "Stopping Services"
    
    # Stop nginx
    if systemctl is-active --quiet nginx; then
        print_info "Stopping nginx..."
        sudo systemctl stop nginx
        print_status "Nginx stopped"
    else
        print_info "Nginx is not running"
    fi
    
    # Stop PHP-FPM (optional)
    if systemctl is-active --quiet php8.3-fpm; then
        print_info "Stopping PHP-FPM..."
        sudo systemctl stop php8.3-fpm
        print_status "PHP-FPM stopped"
    else
        print_info "PHP-FPM is not running"
    fi
}

# Function to remove portfolio-specific configuration
remove_portfolio_config() {
    print_header "Removing Portfolio Configuration"
    
    # Remove portfolio site from sites-enabled
    if [ -L "/etc/nginx/sites-enabled/portfolio" ]; then
        sudo rm /etc/nginx/sites-enabled/portfolio
        print_status "Portfolio site disabled"
    else
        print_info "Portfolio site is not enabled"
    fi
    
    # Remove portfolio site from sites-available
    if [ -f "/etc/nginx/sites-available/portfolio" ]; then
        sudo rm /etc/nginx/sites-available/portfolio
        print_status "Portfolio configuration removed"
    else
        print_info "Portfolio configuration not found"
    fi
    
    # Remove portfolio-specific log files
    if [ -f "/var/log/nginx/portfolio_error.log" ]; then
        sudo rm /var/log/nginx/portfolio_error.log
        print_status "Portfolio error log removed"
    fi
    
    if [ -f "/var/log/nginx/portfolio_access.log" ]; then
        sudo rm /var/log/nginx/portfolio_access.log
        print_status "Portfolio access log removed"
    fi
}

# Function to restore default nginx configuration
restore_default_config() {
    print_header "Restoring Default Configuration"
    
    # Enable default site if it exists
    if [ -f "/etc/nginx/sites-available/default" ] && [ ! -L "/etc/nginx/sites-enabled/default" ]; then
        sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
        print_status "Default nginx site enabled"
    else
        print_info "Default site already enabled or not found"
    fi
    
    # Test nginx configuration
    if sudo nginx -t 2>/dev/null; then
        print_status "Nginx configuration is valid"
    else
        print_error "Nginx configuration has errors"
        sudo nginx -t
        return 1
    fi
}

# Function for complete nginx reset
complete_reset() {
    print_header "Complete Nginx Reset"
    print_warning "This will completely reset nginx to default state!"
    
    read -p "Are you sure you want to perform a complete reset? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_info "Complete reset cancelled"
        return 0
    fi
    
    # Stop services
    stop_services
    
    # Remove nginx package
    print_info "Removing nginx package..."
    sudo apt remove --purge nginx nginx-common nginx-core -y 2>/dev/null || print_warning "Could not remove nginx packages"
    
    # Remove configuration directories
    if [ -d "/etc/nginx" ]; then
        sudo rm -rf /etc/nginx
        print_status "Nginx configuration directory removed"
    fi
    
    # Remove log directories
    if [ -d "/var/log/nginx" ]; then
        sudo rm -rf /var/log/nginx
        print_status "Nginx log directory removed"
    fi
    
    # Remove nginx user (optional)
    if id "www-data" &>/dev/null; then
        print_info "www-data user still exists (this is normal)"
    fi
    
    print_status "Complete nginx reset finished"
    print_info "To reinstall nginx, run: sudo apt install nginx"
}

# Function to start services
start_services() {
    print_header "Starting Services"
    
    # Start nginx
    print_info "Starting nginx..."
    sudo systemctl start nginx
    
    if systemctl is-active --quiet nginx; then
        print_status "Nginx started successfully"
    else
        print_error "Failed to start nginx"
        return 1
    fi
    
    # Start PHP-FPM if available
    if command -v php8.3-fpm &> /dev/null; then
        print_info "Starting PHP-FPM..."
        sudo systemctl start php8.3-fpm
        
        if systemctl is-active --quiet php8.3-fpm; then
            print_status "PHP-FPM started successfully"
        else
            print_warning "PHP-FPM failed to start (this is okay for basic nginx)"
        fi
    fi
}

# Main menu
show_menu() {
    echo
    echo "Select reset option:"
    echo "1) Soft Reset - Stop services and remove portfolio configuration only"
    echo "2) Configuration Reset - Remove portfolio config and restore default"
    echo "3) Service Reset - Stop and restart all nginx services"
    echo "4) Log Reset - Clear nginx log files"
    echo "5) Complete Reset - Completely remove and reset nginx (DESTRUCTIVE)"
    echo "6) Show current status"
    echo "7) Exit"
    echo
    read -p "Choose option (1-7): " choice
    
    case $choice in
        1)
            print_header "Soft Reset"
            create_backup
            stop_services
            remove_portfolio_config
            print_status "Soft reset completed"
            ;;
        2)
            print_header "Configuration Reset"
            create_backup
            stop_services
            remove_portfolio_config
            restore_default_config
            start_services
            print_status "Configuration reset completed"
            ;;
        3)
            print_header "Service Reset"
            stop_services
            start_services
            print_status "Service reset completed"
            ;;
        4)
            print_header "Log Reset"
            create_backup
            if [ -d "/var/log/nginx" ]; then
                sudo find /var/log/nginx -name "*.log" -exec truncate -s 0 {} \;
                print_status "Nginx logs cleared"
            else
                print_info "No nginx log directory found"
            fi
            ;;
        5)
            create_backup
            complete_reset
            ;;
        6)
            print_header "Current Status"
            # Run diagnostic script if available
            if [ -f "diagnose-nginx.sh" ]; then
                bash diagnose-nginx.sh
            else
                # Basic status check
                if systemctl is-active --quiet nginx; then
                    print_status "Nginx is running"
                else
                    print_error "Nginx is not running"
                fi
                
                if [ -f "/etc/nginx/sites-enabled/portfolio" ]; then
                    print_status "Portfolio configuration is active"
                else
                    print_info "Portfolio configuration is not active"
                fi
            fi
            ;;
        7)
            print_info "Exiting..."
            exit 0
            ;;
        *)
            print_error "Invalid option. Please choose 1-7."
            show_menu
            ;;
    esac
}

# Check if nginx is installed
if ! command -v nginx &> /dev/null; then
    print_warning "Nginx is not installed"
    read -p "Do you want to install nginx? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        sudo apt update
        sudo apt install -y nginx
        print_status "Nginx installed"
    else
        print_info "Exiting - nginx not installed"
        exit 0
    fi
fi

# Show menu
show_menu

echo
echo "================================================"
print_status "Reset operation completed!"
echo
echo "Backup location: $BACKUP_DIR"
echo "To restore from backup: sudo cp -r $BACKUP_DIR/nginx /etc/"
echo
echo "Next steps:"
echo "- To reconfigure portfolio: ./setup-nginx.sh"
echo "- To diagnose issues: ./diagnose-nginx.sh"
echo "- To view logs: sudo tail -f /var/log/nginx/error.log"
echo "================================================"