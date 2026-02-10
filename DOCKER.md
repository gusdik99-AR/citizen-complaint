# 🚀 Quick Start Guide

## Setup (First Time)

```bash
# 1. Start all services
docker-compose up -d

# 2. Wait for services to be ready (30 seconds)
sleep 30

# 3. Run initial setup
docker-compose exec app sh docker/scripts/setup.sh

# 4. Access application
open http://localhost:8000
```

## Login Credentials

After running the setup script, you can login with:

**Admin:**
- Email: `admin@pemalang.go.id`
- Password: `admin123`

**OPD (Dinas PU):**
- Email: `pu@pemalang.go.id`
- Password: `opd123`

## Access Points

| Service | URL | Credentials |
|---------|-----|-------------|
| **Laravel App** | http://localhost:8000 | - |
| **Vite Dev Server** | http://localhost:5173 | Auto-connects |
| **MinIO Console** | http://localhost:9001 | minioadmin / minioadmin |
| **Mailpit** | http://localhost:8025 | - |
| **MySQL** | localhost:3306 | laravel / secret |
| **Redis** | localhost:6379 | - |

## Daily Development

```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down
```

## Common Commands

```bash
# Laravel artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Composer
docker-compose exec app composer require vendor/package

# NPM
docker-compose exec app npm install package-name

# Database
docker-compose exec app php artisan migrate:fresh --seed

# Shell access
docker-compose exec app sh
```

## Hot Reload

✅ **Vue components**: Auto-reload (edit `resources/js/`)  
✅ **Laravel code**: Refresh browser (edit `app/`, `routes/`)  
⚠️ **Dependencies**: Rebuild container

## Rebuild After Changes

```bash
# After changing composer.json or package.json
docker-compose build app
docker-compose up -d
```

## Troubleshooting

**Container won't start**:
```bash
docker-compose logs app
docker-compose down -v  # Remove volumes
docker-compose up -d
```

**Permission errors**:
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Database connection error**:
```bash
docker-compose restart db
docker-compose exec app php artisan migrate
```

## Tech Stack

- **Backend**: Laravel 11 + PHP 8.2
- **Frontend**: Vue 3 + Inertia.js
- **Build**: Vite (with HMR)
- **Database**: MySQL 8.0
- **Cache**: Redis 7
- **Storage**: MinIO (S3-compatible)
- **Mail**: Mailpit

## Notes

- 📦 `vendor/` and `node_modules/` are in container (not synced)
- 🔥 Vite HMR works automatically for Vue files
- 🗄️ Database data persists in Docker volumes
- 📧 All emails go to Mailpit (check http://localhost:8025)
