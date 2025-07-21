# 🐳 Docker Setup Guide

## Overview

This project includes Docker configuration for easy deployment and development of the Freight Calculator application. The setup includes PHP 8.1 with Apache, MySQL database, and all necessary dependencies.

## 📋 Prerequisites

- Docker and Docker Compose installed on your system
- Basic knowledge of Docker commands
- Git (to clone the repository)

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/JotaEfi/frete-calc.git
cd frete-calc
```

### 2. Environment Configuration
Copy the example environment file and configure your settings:
```bash
cp .env.example .env
```

Edit the `.env` file with your configuration:
```env
# Database Configuration
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=frete_calc
DB_USERNAME=root
DB_PASSWORD=your_secure_password

# JWT Configuration
JWT_SECRET=your_jwt_secret_key_here
JWT_EXPIRATION=3600

# Application Configuration
APP_ENV=production
APP_DEBUG=false
```

### 3. Build and Start Services
```bash
# Build and start all services
docker-compose up -d

# Or build first then start
docker-compose build
docker-compose up -d
```

### 4. Database Setup
The database will be automatically created and initialized with the required tables and sample data.

### 5. Access the Application
- **Main Application**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin.php
- **API Endpoints**: http://localhost:8080/api.php

## 🗂️ Docker Configuration Files

### Dockerfile
The main Dockerfile configures:
- PHP 8.1 with Apache
- Required PHP extensions (PDO, mysqli, etc.)
- Composer dependencies
- Application files

### docker-compose.yml
Orchestrates two services:
- **web**: PHP/Apache application server
- **mysql**: MySQL 8.0 database server

## 📊 Services Details

### Web Service (PHP/Apache)
- **Port**: 8080
- **PHP Version**: 8.1
- **Web Server**: Apache
- **Document Root**: `/var/www/html`
- **Dependencies**: Composer packages automatically installed

### Database Service (MySQL)
- **Port**: 3306
- **Version**: MySQL 8.0
- **Database**: frete_calc
- **Persistent Storage**: Docker volume `mysql_data`

## 🛠️ Development Commands

### View Running Containers
```bash
docker-compose ps
```

### View Logs
```bash
# All services
docker-compose logs

# Specific service
docker-compose logs web
docker-compose logs mysql
```

### Execute Commands in Containers
```bash
# Access web container bash
docker-compose exec web bash

# Access MySQL CLI
docker-compose exec mysql mysql -u root -p frete_calc
```

### Install PHP Dependencies
```bash
docker-compose exec web composer install
```

### Stop Services
```bash
docker-compose down

# Stop and remove volumes (WARNING: This will delete database data)
docker-compose down -v
```

## 🔧 Troubleshooting

### Common Issues and Solutions

#### 1. Port Already in Use
If port 8080 is already in use, modify `docker-compose.yml`:
```yaml
services:
  web:
    ports:
      - "8081:80"  # Change to available port
```

#### 2. Database Connection Issues
- Ensure MySQL service is running: `docker-compose ps`
- Check database credentials in `.env` file
- Verify database host is set to `mysql` (service name)

#### 3. Permission Issues
```bash
# Fix file permissions
docker-compose exec web chown -R www-data:www-data /var/www/html
docker-compose exec web chmod -R 755 /var/www/html
```

#### 4. Clear Composer Cache
```bash
docker-compose exec web composer clear-cache
```

### View Container Information
```bash
# Inspect web container
docker inspect frete-calc-web

# View container resource usage
docker stats
```

## 📦 Production Deployment

### Build Production Image
```bash
# Build optimized production image
docker build -t frete-calc:production .
```

### Environment Variables for Production
Create a `.env.production` file with secure values:
```env
DB_HOST=your_production_db_host
DB_DATABASE=frete_calc
DB_USERNAME=your_production_user
DB_PASSWORD=your_secure_production_password
JWT_SECRET=your_very_secure_jwt_secret
APP_ENV=production
APP_DEBUG=false
```

### Run Production Container
```bash
docker run -d \
  --name frete-calc-prod \
  -p 80:80 \
  --env-file .env.production \
  frete-calc:production
```

## 🔐 Security Considerations

1. **Change default passwords** in production
2. **Use strong JWT secrets**
3. **Enable HTTPS** in production
4. **Restrict database access** to necessary containers only
5. **Regular security updates** of base images

## 📱 API Testing with Docker

### Test API Endpoints
```bash
# Test vehicles endpoint
curl http://localhost:8080/api.php?action=vehicles

# Test login endpoint
curl -X POST http://localhost:8080/auth-api.php?action=login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@fretecalc.com","password":"admin123"}'
```

## 🗄️ Database Management

### Backup Database
```bash
docker-compose exec mysql mysqldump -u root -p frete_calc > backup.sql
```

### Restore Database
```bash
docker-compose exec -T mysql mysql -u root -p frete_calc < backup.sql
```

### Reset Database
```bash
docker-compose down
docker volume rm frete-calc_mysql_data
docker-compose up -d
```

## 🔄 Updates and Maintenance

### Update Application Code
```bash
git pull origin main
docker-compose build web
docker-compose up -d web
```

### Update Dependencies
```bash
docker-compose exec web composer update
```

### Clean Up Docker Resources
```bash
# Remove unused containers
docker container prune

# Remove unused images
docker image prune

# Remove unused volumes (WARNING: May delete data)
docker volume prune
```

## 📖 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [PHP Docker Official Images](https://hub.docker.com/_/php)
- [MySQL Docker Official Images](https://hub.docker.com/_/mysql)

## 🤝 Support

If you encounter issues with the Docker setup:
1. Check the troubleshooting section above
2. Review Docker and docker-compose logs
3. Ensure all prerequisites are met
4. Open an issue on the project repository

---

**Note**: This Docker configuration is optimized for development and testing. For production deployment, consider additional security measures, monitoring, and backup strategies.