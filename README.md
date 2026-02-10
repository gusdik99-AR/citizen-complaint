# Citizen Complaint System

Sistem Pengaduan Masyarakat - Aplikasi web untuk mengelola pengaduan masyarakat dengan menggunakan Laravel 11, Vue 3, Inertia.js, dan Tailwind CSS.

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.3)
- **Frontend**: Vue 3 dengan Composition API
- **Bridge**: Inertia.js (tanpa API)
- **Styling**: Tailwind CSS 3
- **Build Tool**: Vite

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM >= 9.x
- MySQL/PostgreSQL/SQLite

## Instalasi

### Opsi 1: Menggunakan Docker (Recommended untuk Development)

#### Persyaratan
- Docker Desktop atau Docker Engine
- Docker Compose

#### Quick Start

1. **Clone atau Download Project**
   ```bash
   git clone <repository-url>
   cd citizen-complaint
   ```

2. **Copy Environment File**
   ```bash
   cp .env.example .env
   ```

3. **Build dan Jalankan Containers**
   ```bash
   docker-compose up -d
   ```

4. **Generate Application Key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. **Jalankan Migrasi Database**
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Akses Aplikasi**
   - **Web App**: http://localhost:8000
   - **Vite Dev Server** (hot reload): http://localhost:5173
   - **MinIO Console** (object storage): http://localhost:9001
     - Username: `minioadmin`
     - Password: `minioadmin`
   - **Mailpit** (email testing): http://localhost:8025

#### Perintah Docker Berguna

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f app

# Execute artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Access container shell
docker-compose exec app sh

# Rebuild containers
docker-compose build
docker-compose up -d --force-recreate
```

#### Services yang Tersedia

- **app**: Laravel application dengan hot reload
- **db**: MySQL 8.0 (port 3306)
- **redis**: Redis 7 untuk caching & queues (port 6379)
- **minio**: S3-compatible object storage (ports 9000, 9001)
- **mailpit**: Email testing tool (ports 1025, 8025)

### Opsi 2: Instalasi Manual

### 1. Clone atau Download Project

```bash
cd c:\laragon\www
# Jika sudah ada folder citizen-complaint
cd citizen-complaint
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies JavaScript

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
# Copy file .env.example menjadi .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan pengaturan database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citizen_complaint
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. Build Assets

```bash
# Development (watch mode)
npm run dev

# Production
npm run build
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

## Struktur Folder

```
citizen-complaint/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── HomeController.php       # Controller untuk homepage
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php # Middleware Inertia
│   └── Models/
├── bootstrap/
│   └── app.php                           # Bootstrap aplikasi & register middleware
├── config/                               # File konfigurasi Laravel
├── database/
│   ├── migrations/                       # Database migrations
│   └── seeders/                         # Database seeders
├── public/                              # Public assets
├── resources/
│   ├── css/
│   │   └── app.css                      # Tailwind CSS directives
│   ├── js/
│   │   ├── app.js                       # Entry point Vue + Inertia
│   │   ├── Components/                  # Vue Components
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue           # Layout utama
│   │   └── Pages/
│   │       └── Home.vue                # Homepage
│   └── views/
│       └── app.blade.php               # Root template Inertia
├── routes/
│   └── web.php                         # Web routes
├── .editorconfig                       # Editor configuration
├── .prettierrc                         # Prettier configuration
├── composer.json                       # PHP dependencies
├── package.json                        # NPM dependencies
├── postcss.config.js                  # PostCSS configuration
├── tailwind.config.js                 # Tailwind configuration
└── vite.config.js                     # Vite configuration
```

## Dependencies

### Composer (PHP)

```json
{
  "php": "^8.2",
  "laravel/framework": "^11.0",
  "laravel/tinker": "^2.9",
  "inertiajs/inertia-laravel": "^1.3"
}
```

### NPM (JavaScript)

```json
{
  "dependencies": {
    "@inertiajs/vue3": "^1.0.14",
    "vue": "^3.4.15"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.0.3",
    "autoprefixer": "^10.4.17",
    "axios": "^1.6.4",
    "laravel-vite-plugin": "^1.0",
    "postcss": "^8.4.35",
    "tailwindcss": "^3.4.1",
    "vite": "^5.0"
  }
}
```

## Perintah Berguna

```bash
# Development
npm run dev              # Jalankan Vite dev server
php artisan serve        # Jalankan Laravel development server

# Production
npm run build           # Build assets untuk production

# Database
php artisan migrate                    # Jalankan migrations
php artisan migrate:fresh              # Drop all tables & re-migrate
php artisan migrate:fresh --seed       # + run seeders
php artisan db:seed                    # Jalankan seeders

# Clear Cache
php artisan cache:clear        # Clear application cache
php artisan config:clear       # Clear config cache
php artisan route:clear        # Clear route cache
php artisan view:clear         # Clear compiled views
```

## Docker Deployment

### Development Environment

Docker Compose sudah dikonfigurasi untuk development dengan:
- Hot reload untuk Vite
- Volume mounts untuk live code changes
- MySQL, Redis, MinIO, dan Mailpit

```bash
docker-compose up -d
```

### Production Deployment

Untuk production, gunakan Dockerfile dengan target `production`:

```bash
# Build production image
docker build --target production -t citizen-complaint:latest .

# Run production container
docker run -d \
  -p 8000:8000 \
  --env-file .env.production \
  --name citizen-complaint \
  citizen-complaint:latest
```

**Catatan**: Untuk production, database, Redis, dan object storage harus dikonfigurasi secara terpisah (tidak menggunakan docker-compose).

## Catatan Penting

1. **Tidak Menggunakan Breeze/Jetstream**: Setup dilakukan secara manual untuk pembelajaran
2. **Tidak Ada Auth**: Project ini fokus pada skeleton dasar, auth bisa ditambahkan nanti
3. **Tidak Ada API**: Menggunakan Inertia.js sebagai pengganti REST API
4. **Struktur Sederhana**: Menggunakan struktur MVC default Laravel, bukan modular

## File Konfigurasi Tersedia

### .editorconfig

Menjaga konsistensi formatting antar editor

### .prettierrc

Konfigurasi Prettier untuk auto-formatting JavaScript/Vue

### tailwind.config.js

Konfigurasi Tailwind CSS (content paths, theme, plugins)

### vite.config.js

Konfigurasi Vite (Vue plugin, aliases, Laravel integration)

## Troubleshooting

### Error: Cannot find module '@inertiajs/vue3'

```bash
npm install
```

### Error: Class 'Inertia\Middleware' not found

```bash
composer install
```

### Assets tidak ter-load

```bash
npm run build
php artisan optimize:clear
```

### Database connection error

Pastikan database sudah dibuat dan konfigurasi `.env` sudah benar

## Lisensi

MIT License

## Author

Dibuat untuk pembelajaran mahasiswa - Laravel 11 + Vue 3 + Inertia + Tailwind
