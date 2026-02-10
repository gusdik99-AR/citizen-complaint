#!/bin/sh
set -e

echo "🚀 Starting Citizen Complaint System setup..."

# Wait for database to be ready
echo "⏳ Waiting for database..."
until php artisan db:show > /dev/null 2>&1; do
  echo "Database is unavailable - sleeping"
  sleep 2
done

echo "✅ Database is ready!"

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate

# Run seeders
echo "🌱 Seeding database..."
php artisan db:seed

# Clear and cache config
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Setup complete!"
echo ""
echo "📍 Access points:"
echo "   - Web App: http://localhost:8000"
echo "   - Vite Dev Server: http://localhost:5173"
echo "   - MinIO Console: http://localhost:9001"
echo "   - Mailpit: http://localhost:8025"
echo ""
