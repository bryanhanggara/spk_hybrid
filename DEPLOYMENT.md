# Panduan Deployment Sistem SPK

## Persiapan Deployment

### 1. Requirements
- **Server**: Ubuntu 20.04+ / CentOS 7+ / Windows Server 2019+
- **PHP**: 8.1+ dengan ekstensi:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML
- **Database**: MySQL 8.0+ / MariaDB 10.3+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Node.js**: 16+ (untuk build assets)

### 2. Server Setup

#### Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath php8.1-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

# Install Nginx
sudo apt install nginx
```

#### CentOS/RHEL
```bash
# Install EPEL repository
sudo yum install epel-release

# Install PHP 8.1
sudo yum install https://rpms.remirepo.net/enterprise/remi-release-8.rpm
sudo yum module enable php:remi-8.1
sudo yum install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-bcmath php-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install nodejs

# Install MySQL
sudo yum install mysql-server
sudo systemctl start mysqld
sudo mysql_secure_installation

# Install Nginx
sudo yum install nginx
```

## Konfigurasi Database

### 1. Buat Database
```sql
CREATE DATABASE spk_skripsi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'spk_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON spk_skripsi.* TO 'spk_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Konfigurasi .env
```env
APP_NAME="Sistem SPK"
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_skripsi
DB_USERNAME=spk_user
DB_PASSWORD=strong_password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Deployment Steps

### 1. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/yourusername/spk-skripsi.git
sudo chown -R www-data:www-data spk-skripsi
cd spk-skripsi
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install

# Build assets
npm run build
```

### 3. Laravel Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --class=StudentSeeder

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/spk-skripsi/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/spk-skripsi/public

    <Directory /var/www/spk-skripsi/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/spk_error.log
    CustomLog ${APACHE_LOG_DIR}/spk_access.log combined
</VirtualHost>
```

### 5. SSL Configuration (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 6. Firewall Configuration
```bash
# UFW (Ubuntu)
sudo ufw allow 'Nginx Full'
sudo ufw allow ssh
sudo ufw enable

# Firewalld (CentOS)
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

## Production Optimization

### 1. PHP-FPM Configuration
```ini
; /etc/php/8.1/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 2. MySQL Optimization
```ini
; /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
```

### 3. Laravel Optimization
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Queue configuration (if needed)
php artisan queue:work --daemon
```

## Monitoring & Logging

### 1. Log Configuration
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.1-fpm.log
```

### 2. Monitoring Tools
```bash
# Install htop for system monitoring
sudo apt install htop

# Install monitoring tools
sudo apt install iotop nethogs
```

### 3. Backup Configuration
```bash
# Create backup script
sudo nano /usr/local/bin/backup-spk.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/spk"
DB_NAME="spk_skripsi"
DB_USER="spk_user"
DB_PASS="strong_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/spk-skripsi

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/backup-spk.sh

# Add to crontab
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-spk.sh
```

## Security Hardening

### 1. File Permissions
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/spk-skripsi
sudo chmod -R 755 /var/www/spk-skripsi
sudo chmod -R 775 /var/www/spk-skripsi/storage
sudo chmod -R 775 /var/www/spk-skripsi/bootstrap/cache
```

### 2. Security Headers
```nginx
# Add to Nginx configuration
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
```

### 3. Database Security
```sql
-- Remove test database
DROP DATABASE IF EXISTS test;

-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Remove remote root access
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Flush privileges
FLUSH PRIVILEGES;
```

## Troubleshooting

### 1. Common Issues

#### 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check PHP-FPM logs
tail -f /var/log/php8.1-fpm.log

# Check Nginx logs
tail -f /var/log/nginx/error.log
```

#### Database Connection Error
```bash
# Test database connection
php artisan tinker
# In tinker: DB::connection()->getPdo();
```

#### Permission Issues
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/spk-skripsi
sudo chmod -R 775 storage bootstrap/cache
```

### 2. Performance Issues

#### Slow Page Load
```bash
# Check PHP-FPM processes
sudo systemctl status php8.1-fpm

# Check MySQL processes
sudo mysql -e "SHOW PROCESSLIST;"

# Check disk space
df -h
```

#### High Memory Usage
```bash
# Check memory usage
free -h
htop

# Optimize PHP-FPM
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

## Maintenance

### 1. Regular Updates
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Update Composer packages
composer update

# Update Node.js packages
npm update
```

### 2. Log Rotation
```bash
# Configure logrotate
sudo nano /etc/logrotate.d/spk
```

```
/var/www/spk-skripsi/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

### 3. Health Checks
```bash
# Create health check script
sudo nano /usr/local/bin/health-check.sh
```

```bash
#!/bin/bash
# Check if application is running
curl -f http://localhost/health || exit 1

# Check database connection
php /var/www/spk-skripsi/artisan tinker --execute="DB::connection()->getPdo();" || exit 1

# Check disk space
df -h | awk '$5 > 80 {print "Disk space warning: " $0}' || exit 1
```

## Conclusion

Deployment ini memastikan sistem SPK berjalan dengan optimal di lingkungan produksi. Pastikan untuk melakukan testing menyeluruh setelah deployment dan setup monitoring untuk memantau kesehatan aplikasi.

