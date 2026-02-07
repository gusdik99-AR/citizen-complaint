# Panduan Instalasi Step-by-Step

## Citizen Complaint System - Laravel 11 + Vue 3 + Inertia + Tailwind

---

## LANGKAH 1: Persiapan

### 1.1 Pastikan Requirements Terinstall

```bash
# Cek PHP version (minimal 8.2)
php -v

# Cek Composer
composer --version

# Cek Node.js (minimal v18)
node -v

# Cek NPM
npm -v
```

### 1.2 Navigasi ke Folder Project

```bash
cd c:\laragon\www\citizen-complaint
```

---

## LANGKAH 2: Install Dependencies Backend (PHP)

### 2.1 Fix SSL Certificate Issue (Jika Ada)

Jika muncul error SSL certificate saat `composer install`, jalankan:

```bash
# Cara 1: Disable SSL verification (development only)
composer config -g -- disable-tls false
composer config -g -- secure-http false

# Cara 2: Update CA Bundle
composer self-update --update-keys
```

### 2.2 Install Composer Packages

```bash
# Install semua dependencies
composer install

# Atau jika sudah pernah install
composer update
```

### 2.3 Verifikasi Instalasi

```bash
# Cek apakah laravel bisa dijalankan
php artisan --version
```

Expected output: `Laravel Framework 11.x.x`

---

## LANGKAH 3: Setup Environment

### 3.1 Copy Environment File

```bash
# Windows (PowerShell)
copy .env.example .env

# Atau manual: duplicate .env.example dan rename jadi .env
```

### 3.2 Generate Application Key

```bash
php artisan key:generate
```

### 3.3 Konfigurasi Database di .env

Edit file `.env` dengan text editor, sesuaikan:

```env
APP_NAME="Citizen Complaint"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citizen_complaint
DB_USERNAME=root
DB_PASSWORD=
```

### 3.4 Buat Database

Buka phpMyAdmin atau MySQL client, buat database baru:

```sql
CREATE DATABASE citizen_complaint;
```

---

## LANGKAH 4: Install Dependencies Frontend (NPM)

### 4.1 Install Node Packages

```bash
npm install
```

Ini akan menginstall:

- Vue 3
- Inertia.js client
- Tailwind CSS
- Vite
- dan dependencies lainnya

### 4.2 Verifikasi package.json

Pastikan file `package.json` memiliki dependencies berikut:

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

---

## LANGKAH 5: Jalankan Database Migration

```bash
php artisan migrate
```

Expected output:

```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table
...
```

---

## LANGKAH 6: Build Frontend Assets

### Untuk Development dengan Hot Reload:

```bash
npm run dev
```

Biarkan terminal ini tetap berjalan. Output:

```
VITE v5.x.x ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

### Untuk Production Build:

```bash
npm run build
```

---

## LANGKAH 7: Jalankan Laravel Development Server

Buka terminal baru (jangan tutup terminal `npm run dev`):

```bash
php artisan serve
```

Output:

```
INFO  Server running on [http://127.0.0.1:8000].
```

---

## LANGKAH 8: Test Aplikasi

1. Buka browser
2. Akses: `http://localhost:8000`
3. Anda akan melihat homepage Citizen Complaint System

---

## Troubleshooting

### Error: "Target class [HomeController] does not exist"

**Solusi:**

```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "Cannot find module '@inertiajs/vue3'"

**Solusi:**

```bash
# Hapus folder node_modules dan package-lock.json
rm -r -fo node_modules
rm package-lock.json

# Install ulang
npm install
```

### Error: "SQLSTATE[HY000] [1049] Unknown database"

**Solusi:**

- Pastikan database `citizen_complaint` sudah dibuat
- Cek konfigurasi `.env` untuk DB_DATABASE, DB_USERNAME, DB_PASSWORD

### Error: "Vite manifest not found"

**Solusi:**

```bash
# Pastikan npm run dev sedang berjalan, atau
npm run build
```

### Assets tidak muncul / styling tidak ada

**Solusi:**

```bash
# Restart Vite dev server
npm run dev

# Atau build ulang
npm run build

# Clear cache Laravel
php artisan optimize:clear
```

### Error SSL Certificate (Composer)

**Solusi Sementara (Development Only):**

```bash
composer config -g -- disable-tls false
composer config -g -- secure-http false
```

**Solusi Permanent:**

1. Download CA Bundle: https://curl.se/ca/cacert.pem
2. Simpan di `C:\laragon\etc\ssl\cacert.pem`
3. Edit `php.ini`, tambahkan:
   ```ini
   curl.cainfo = "C:\laragon\etc\ssl\cacert.pem"
   ```

---

## Perintah Sehari-hari

### Development Mode (2 Terminal):

**Terminal 1 - Frontend:**

```bash
cd c:\laragon\www\citizen-complaint
npm run dev
```

**Terminal 2 - Backend:**

```bash
cd c:\laragon\www\citizen-complaint
php artisan serve
```

### Clear All Cache:

```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset Database:

```bash
php artisan migrate:fresh
# Atau dengan seeders
php artisan migrate:fresh --seed
```

---

## File-File Penting yang Sudah Dikonfigurasi

✅ `composer.json` - Dependencies PHP termasuk Inertia Laravel  
✅ `package.json` - Dependencies NPM (Vue, Inertia, Tailwind)  
✅ `vite.config.js` - Konfigurasi Vite + Vue plugin  
✅ `tailwind.config.js` - Konfigurasi Tailwind CSS  
✅ `postcss.config.js` - PostCSS dengan Tailwind  
✅ `resources/js/app.js` - Entry point Vue + Inertia  
✅ `resources/css/app.css` - Tailwind directives  
✅ `resources/views/app.blade.php` - Root template Inertia  
✅ `app/Http/Middleware/HandleInertiaRequests.php` - Inertia middleware  
✅ `bootstrap/app.php` - Bootstrap & register middleware  
✅ `routes/web.php` - Routes dengan HomeController  
✅ `app/Http/Controllers/HomeController.php` - Homepage controller  
✅ `resources/js/Layouts/AppLayout.vue` - Layout dasar  
✅ `resources/js/Pages/Home.vue` - Homepage Vue component

---

## Struktur Project Final

```
citizen-complaint/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   └── HomeController.php ← Controller homepage
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php ← Middleware Inertia
│   └── Models/
│       └── User.php
├── bootstrap/
│   └── app.php ← Middleware registration
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── build/ ← Generated assets (setelah npm run build)
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css ← Tailwind directives
│   ├── js/
│   │   ├── app.js ← Vue + Inertia initialization
│   │   ├── Components/ ← Vue components (custom)
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue ← Layout utama
│   │   └── Pages/
│   │       └── Home.vue ← Homepage
│   └── views/
│       └── app.blade.php ← Root template
├── routes/
│   ├── web.php ← Web routes
│   └── console.php
├── .editorconfig
├── .env ← Environment configuration
├── .gitignore
├── .prettierrc
├── composer.json
├── package.json
├── postcss.config.js
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## Next Steps

Setelah instalasi berhasil, Anda bisa mulai develop fitur:

1. **Buat Model & Migration baru:**

   ```bash
   php artisan make:model Complaint -m
   ```

2. **Buat Controller baru:**

   ```bash
   php artisan make:controller ComplaintController
   ```

3. **Buat Vue Page baru:**
   - Buat file di `resources/js/Pages/ComplaintForm.vue`
   - Tambahkan route di `routes/web.php`

4. **Buat Component baru:**
   - Buat file di `resources/js/Components/Button.vue`
   - Import di page yang membutuhkan

---

## Support

Jika masih ada error atau pertanyaan, cek:

- Laravel Docs: https://laravel.com/docs/11.x
- Inertia.js Docs: https://inertiajs.com/
- Vue 3 Docs: https://vuejs.org/
- Tailwind CSS Docs: https://tailwindcss.com/

---

**Happy Coding! 🚀**
