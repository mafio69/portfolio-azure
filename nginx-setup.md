# Nginx Setup Guide for Portfolio Application

This guide will help you configure nginx to serve your portfolio application in Ubuntu WSL.

## Prerequisites

1. **Install nginx**:
   ```bash
   sudo apt update
   sudo apt install nginx
   ```

2. **Install PHP-FPM** (for production setup):
   ```bash
   sudo apt install php8.3-fpm php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath
   ```

3. **Start services**:
   ```bash
   sudo systemctl start nginx
   sudo systemctl start php8.3-fpm
   sudo systemctl enable nginx
   sudo systemctl enable php8.3-fpm
   ```

## Setup Steps

### 1. Development Setup (Recommended for testing)

This setup uses nginx as a reverse proxy to your existing development servers:

1. **Copy the nginx configuration**:
   ```bash
   sudo cp nginx.conf /etc/nginx/sites-available/portfolio
   sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/
   ```

2. **Remove default nginx site** (optional):
   ```bash
   sudo rm /etc/nginx/sites-enabled/default
   ```

3. **Start your development servers**:
   ```bash
   # Terminal 1: Start PHP backend
   ./start-backend.sh
   
   # Terminal 2: Start Vite frontend (default port 5173)
   cd frontend
   npm run dev
   ```

4. **Update nginx config for Vite dev server**:
   Edit `/etc/nginx/sites-available/portfolio` and uncomment the Vite proxy lines in the `@frontend` section:
   ```nginx
   location @frontend {
       proxy_pass http://localhost:5173;  # Vite default port
       proxy_http_version 1.1;
       proxy_set_header Upgrade $http_upgrade;
       proxy_set_header Connection 'upgrade';
       proxy_set_header Host $host;
       proxy_cache_bypass $http_upgrade;
   }
   ```

5. **Test nginx configuration and reload**:
   ```bash
   sudo nginx -t
   sudo systemctl reload nginx
   ```

6. **Access your application**:
   - Frontend: http://localhost
   - API: http://localhost/api

### 2. Production Setup

For a full production setup where nginx serves static files directly:

1. **Build the frontend**:
   ```bash
   cd frontend
   npm run build
   ```

2. **Use the production nginx config** (already configured in nginx.conf)

3. **Configure PHP-FPM** (if using direct PHP serving):
   - Uncomment the production PHP sections in the nginx config
   - Comment out the proxy_pass lines

### 3. Troubleshooting

1. **Check nginx status**:
   ```bash
   sudo systemctl status nginx
   ```

2. **Check nginx error logs**:
   ```bash
   sudo tail -f /var/log/nginx/portfolio_error.log
   ```

3. **Check if ports are available**:
   ```bash
   sudo netstat -tlnp | grep :80
   sudo netstat -tlnp | grep :8000
   sudo netstat -tlnp | grep :5173
   ```

4. **Test nginx configuration**:
   ```bash
   sudo nginx -t
   ```

5. **Restart nginx**:
   ```bash
   sudo systemctl restart nginx
   ```

## Configuration Details

The nginx configuration provides:

- **Frontend serving**: Static files and SPA routing support
- **Backend API**: Proxying to PHP backend with `/api` prefix
- **Security headers**: XSS protection, content type sniffing prevention
- **Performance**: Gzip compression, static asset caching
- **Development mode**: Proxy to Vite dev server and PHP built-in server
- **Production mode**: Direct serving of built files and PHP-FPM integration

## Common Issues

1. **Permission errors**: Make sure nginx can read the project files:
   ```bash
   sudo chown -R www-data:www-data /home/mariusz/projects/portfolio_azure/frontend/dist
   ```

2. **PHP socket not found**: Check PHP-FPM socket location:
   ```bash
   sudo find /var/run -name "*php*sock*"
   ```

3. **Port conflicts**: Make sure no other services are using port 80:
   ```bash
   sudo systemctl stop apache2  # if Apache is running
   ```