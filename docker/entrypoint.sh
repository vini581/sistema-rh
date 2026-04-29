#!/bin/bash
set -e

cd /var/www

echo "⚙️  Configurando .env..."
cp .env.example .env

sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=mysql|' .env
sed -i 's|^.*DB_HOST=.*|DB_HOST=db|' .env
sed -i 's|^.*DB_PORT=.*|DB_PORT=3306|' .env
sed -i 's|^.*DB_DATABASE=.*|DB_DATABASE=sistema_rh|' .env
sed -i 's|^.*DB_USERNAME=.*|DB_USERNAME=laravel|' .env
sed -i 's|^.*DB_PASSWORD=.*|DB_PASSWORD=secret|' .env

echo "🔑 Gerando APP_KEY..."
php artisan key:generate --force

echo "🗄️  Rodando migrations..."
php artisan migrate --force

echo "🌱 Rodando seeders..."
php artisan db:seed --force 2>/dev/null || echo "Seeder ignorado"

echo "🔗 Criando storage link..."
php artisan storage:link --force 2>/dev/null || true

echo "⚡ Otimizando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Sistema pronto em http://127.0.0.1:8000"
echo "   📧 admin@gmail.com  |  🔑 654321"
echo ""

php-fpm -D
nginx -g "daemon off;"